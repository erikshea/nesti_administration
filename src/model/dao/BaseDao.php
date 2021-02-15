<?php
class BaseDao
{
    protected static $cachedData = ['columnNames' => [], 'columnDefaults'=>[]];
    protected static $pkColumns = null;

    public const FLAGS = [
        'active'  => 'a',
        'waiting' => 'w',
        'blocked' => 'b'
    ];

    /**
     * getTableName
     * get corresponding table name in data source, ie "recipe"
     * @return String table name in data source
     */
    public static function getTableName(): String
    {
        return strtolower(static::getEntityClass());
    }

    /**
     * getEntityClass
     * get corresponding entity class name in data source, ie "Recipe"
     * @return String entity class name in data source
     */
    public static function getEntityClass(): String
    {
        return substr(get_called_class(), 0, -3);
    }

    /**
     * getEntityClass
     * get primary key column of current table, ie "RecipeId"
     * or array of names if composite primary key
     * @return mixed entity class name in data source
     */
    public static function getPkColumnName()
    {
        if (static::$pkColumns == null) {
            $pkCol = 'id' . static::getEntityClass();
        } else {
            $pkCol = static::$pkColumns;

            if (count($pkCol) == 1) {
                $pkCol = $pkCol[0];
            }
        }

        return $pkCol;
    }

    /**
     * initializeQueryOptions
     *
     * @param  mixed $options either a string (flag to look for), or an array of query options
     *                 ie: [ 'articlePrice >=' => 12, 'articleName' => 'Marlboro Lights']
     * @return void initialized array of options
     */
    protected static function initializeQueryOptions(&$options)
    {
        // if null, return empty array
        if ($options == null) {
            $options = [];
        } elseif (!is_array($options)) {
            // if options is a simple string, assume we're looking for a flag 
            $options = ['flag' => $options];
        }
    }

    /**
     * buildRequest
     *
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @param  string $sql starting SQL string, if none specified we assume we're selecting all from current table
     * @param  mixed $values values to inject in prepared query (in case the starting sql string uses some)
     * @return PDOStatement built and executed statement
     */
    protected static function buildRequest(array &$options, ?string $sql = null, array $values = []): PDOStatement
    {
        $pdo = DatabaseUtil::getConnection();

        // if no starting sql specified, select all
        if ($sql == null) {
            $sql = "SELECT * FROM " . static::getTableName();
        }


        // $options may be in the form [ 'articlePrice <=' => 12,
        //                               'flag' => 'a',
        //                               'HOUR(creationDate) >=' => 9,
        //                               'HOUR(creationDate) <' => 17 ]
        // we must pick out the property name for later validation
        $conditions = [];
        array_walk($options, function ($value, $key) use (&$conditions, &$options) {
            if (preg_match(
                "/^(.*)(=|>|<|>=|<=|<>|!=|LIKE|NOT LIKE)$/", // look for an operator at the end of option key
                $key,
                $matches
            )) {
                $propertyKey = trim($matches[1]);
                $operator  = $matches[2];
            } else {
                // if no operator specified, assume "="
                $propertyKey = trim($key);
                $operator = "=";
            }

            $condition = "$propertyKey $operator ?";

            if (preg_match(
                "/^.*\((.*)\)$/", // is remaining part of key an sql function call?
                $propertyKey,
                $matches
            )) {
                // if so, actual property key is within the parentheses
                $propertyKey = trim($matches[1]);
            }

            // only add if it has an equivalent table column
            if (in_array($propertyKey, static::getColumnNames())) {
                $conditions[] = ['condition' => $condition, 'value' => $value];
                // remove property from options in case we reuse the same options in parent entity query
                unset($options[$key]);
            }
        });

        // $translatedOptions is now in the form 
        // [ [ 'condition' => 'articlePrice <= ?',
        //     'value' => 12 ],
        //   [ 'condition' => 'flag = ?',
        //     'value' => 'a'] ]
        //   [ 'condition' => 'HOUR(creationDate) >= ?',
        //     'value' => 8] ]
        //   [ 'condition' => 'HOUR(creationDate) < ?',
        //     'value' => 15] ]

        foreach ($conditions as $i => $optionParameters) {
            if ($i == array_key_first($conditions)) {
                $sql .= " WHERE ";
            }

            $sql .= $optionParameters['condition'];
            $values[] = $optionParameters['value'];

            // don't add another AND if we're done adding WHERE conditions
            if ($i != array_key_last($conditions)) {
                $sql .= " AND ";
            }
        }


        if (isset($options['ORDER'])) {
            if (preg_match(
                "/^(.*) (DESC|ASC)$/", // look for an operator at the end of ORDER option
                $options['ORDER'],
                $matches
            )) { // if order option in the form 'ORDER' => 'articlePrice ASC' or 'ORDER' => 'articlePrice DESC'
                $sql .= " ORDER BY {$matches[1]} {$matches[2]}";
            } else { // if order option in the form 'ORDER' => 'articlePrice', assume DESC
                $sql .= " ORDER BY {$options['ORDER']} DESC";
            }
            unset($options['ORDER']);
        } elseif (in_array(static::getPkColumnName(), static::getColumnNames())) {
            $sql .= " ORDER BY " . static::getPkColumnName() . " ASC";
        }

        if (isset($options['LIMIT'])) {
            $sql .= " LIMIT " . $options['LIMIT'];
            unset($options['LIMIT']);
        }

        if (isset($options['OFFSET'])) {
            $sql .= " OFFSET " . $options['OFFSET'];
            unset($options['OFFSET']);
        }

        $request = $pdo->prepare($sql);
        $request->execute($values);

        return $request;
    }


    /**
     * fetchEntity
     * transforms a request result row into an entity. 
     * if entity is based on an inherited table, loops through all parent tables to populate entity properties
     * @param  mixed $req
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return void
     */
    protected static function fetchEntity(&$req, array $queryOptions)
    {
        // start by initializing entity as a fetched object of current request
        $entity = $req->fetchObject(static::getEntityClass());
        $currentClass = get_parent_class(static::getEntityClass());

        // loop through all parent entities
        while ($entity != null && $currentClass != 'BaseEntity') {
            $currentDao = $currentClass::getDaoClass();
            $currentQueryOptions = $queryOptions;

            $currentQueryOptions[$currentDao::getPkColumnName()] = $entity->getId();

            $currentRequest = $currentDao::buildRequest($currentQueryOptions);

            // get row data as associative array, set initial entity's properties to that of current child's properties
            $rowData = $currentRequest->fetch(PDO::FETCH_ASSOC);

            if ($rowData === false) {
                $entity = null;  // if a query option constraint fails (ie blocked flag in a parent)
            } else {
                EntityUtil::setFromArray($entity, $rowData);
            }

            $currentClass = get_parent_class($currentClass);
        }

        return $entity;
    }


    /**
     * findAll
     * fetch all table rows, create and return matching entitiess
     * @return Array of entities (or empty if no rows in table)
     */
    public static function findAll($options = null): array
    {
        static::initializeQueryOptions($options);

        $req = static::buildRequest($options);

        return static::getEntitiesFromRequest($req, $options);
    }


    /**
     * findOne
     * create and return an entity corresponding to the given query options
     * @param  String $key field name
     * @param  mixed $value to search
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return mixed entity if found, null otherwise
     */
    public static function findOne($options = null)
    {
        static::initializeQueryOptions($options);

        return static::findAll($options)[0] ?? null;
    }


    /**
     * findOneBy
     * create and return an entity corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return mixed entity if found, null otherwise
     */
    public static function findOneBy(string $key, $value, $options = null)
    {
        static::initializeQueryOptions($options);

        $options[$key] = $value;

        return static::findOne($options);
    }

    /**
     * findById
     * create and return an entity corresponding to a given id
     * @param  mixed $id primary key
     * @return mixed if found, null otherwise
     */
    public static function findById($id, $options = null)
    {
        static::initializeQueryOptions($options);

        $options[static::getPkColumnName()] = $id;

        return static::findOne($options);
    }


    /**
     * findOneBy
     * create and return an array of entities corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @return array of entities
     */
    public static function findAllBy(String $key, $value, $options = null)
    {
        static::initializeQueryOptions($options);

        $options[$key] = $value;

        return static::findAll($options);
    }


    /**
     * delete
     * remove row in data source that corresponds to an Entity
     * @param  mixed $entity entity to remove from data source
     * @return void
     */
    public static function delete($entity)
    {
        $pdo = DatabaseUtil::getConnection();
        $sql = "DELETE FROM " . static::getTableName() . " WHERE ";

        $values = [];
        $columns = [];
        $pkColumns = [];
        if (!is_array(static::getPkColumnName())) {
            $pkColumns = [static::getPkColumnName()];
        } else {
            $pkColumns = static::getPkColumnName();
        }

        foreach ($pkColumns as $pkColumnName) {
            $values[] = EntityUtil::get($entity, $pkColumnName);
        }

        $whereConditions = array_map(function ($columnName) {
            return "$columnName = ?";
        }, $pkColumns);
        $sql .= implode(" AND ", $whereConditions);

        $q = $pdo->prepare($sql);
        $q->execute($values);
    }

    /**
     * saveOrUpdate
     * Save an entity to data source if it exists, or insert a new row if it doesn't
     * @param  mixed $entity
     * @return void
     */
    public static function saveOrUpdate(&$entity, $skipNullIfDefaultValue = true)
    {
        $pdo = DatabaseUtil::getConnection();
        if ($entity->existsInDataSource()) {
            static::update($entity, $skipNullIfDefaultValue);
        } else {
            static::save($entity, $skipNullIfDefaultValue);
        }
    }

    /**
     * update
     * update the table row that corresponds to an entity
     * @param  BaseEntity $entity to update in table
     * @return void
     */
    public static function update(&$entity, $skipNullIfDefaultValue = true)
    {
        // Loop through inherited tables (from parent to child), updating the relevant entity properties
        foreach (static::getParentClasses() as $currentClass) {
            $pdo = DatabaseUtil::getConnection();
            $currentDao = $currentClass::getDaoClass();
            
            $columnNames = $currentDao::getColumnNames();

            // if we're not saving null values in columns where data source can set defaults (such as current timestamp)
            if ($skipNullIfDefaultValue) {
                $columnNames = array_values(array_filter($columnNames, function ($name) use ($entity) {
                    return  !(isset( static::getColumnDefaults()[$name] ) && EntityUtil::get($entity, $name) == null);
                }));
            }

            // update conditions are in the form "COLUMN_NAME = ?, COLUMN_NAME2 = ?, ..."
            $conditions = array_map(function ($columnName) {
                return "$columnName = ?";
            }, $columnNames);

            $sql = "UPDATE " . $currentDao::getTableName() . " SET " . implode(',', $conditions) . " WHERE ";

            $values = array_map( // Create a new array out of the column names
                function ($columnName) use ($entity) {
                    return EntityUtil::get($entity, $columnName); // Each column name corresponds to an entity getter 
                },
                $columnNames
            );


            $pkColumns = [];
            if (!$entity->hasCompositeKey()) {
                $pkColumns = [$currentDao::getPkColumnName()];
            } else {
                $pkColumns = $currentDao::getPkColumnName();
            }

            foreach ($pkColumns as $pkColumnName) {
                $values[] = EntityUtil::get($entity, $pkColumnName);
            }

            $whereConditions = array_map(function ($columnName) {
                return "$columnName = ?";
            }, $pkColumns);

            $sql .= implode(" AND ", $whereConditions);

            $q = $pdo->prepare($sql);

            $q->execute($values);
        }
    }



    /**
     * save
     * insert a new row into data source that corresponds to an entity
     * @param  BaseEntity $entity to base new row on
     * @return int either inserted ID (if auto-incremented), or existing composite pk
     */
    public static function save(&$entity, $skipNullIfDefaultValue = true)
    {
        $insertedId = null;
        // Loop through inherited tables (from parent to child), inserting the relevant entity properties
        foreach (static::getParentClasses() as $currentClass) {
            $pdo = DatabaseUtil::getConnection();
            $currentDao = $currentClass::getDaoClass();

            if (!$entity->hasCompositeKey() && $currentDao::findById($entity->getId()) != null) {
                $insertedId = $entity->getId();
                continue;
            }

            // get column names for current table. only include IDs if entity has composite PK 
            // (else let data source deal with creating a new one)
            $columnNames = $currentDao::getColumnNames($entity->hasCompositeKey());

            // if we're not saving null values in columns where data source can set defaults (such as current timestamp)
            if ($skipNullIfDefaultValue) {
                $columnNames = array_values(array_filter($columnNames, function ($name) use ($entity) {
                    return  !(isset( static::getColumnDefaults()[$name] ) && EntityUtil::get($entity, $name) == null);
                }));
            }

            // populate values with the entity properties that correspond to the column names
            $values = array_map(function ($columnName) use ($entity) {
                return EntityUtil::get($entity, $columnName);
            }, $columnNames);

            // if we're dealing with an inherited table, we must insert parent id explicitly to child table
            if (static::hasParentEntity($currentClass)) {
                // if composite key, just insert existing pks
                if ($entity->hasCompositeKey()) {
                    array_push($columnNames, $currentDao::getPkColumnName());
                    array_push($values, $entity->getId());
                } else { //else, id was auto-incremented so insert last inserted id
                    $columnNames[] = $currentDao::getPkColumnName();
                    $values[] = $insertedId;
                }
            }
            // Need a list of question marks of same size as the list of column names
            $questionMarks = array_map(function ($columnName) {
                return '?';
            }, $columnNames);

            $sql = "INSERT INTO " . $currentDao::getTableName() . " (" . implode(',', $columnNames) . ") 
            values(" . implode(',', $questionMarks) . ")";


            $q = $pdo->prepare($sql);

            $q->execute($values);


            $insertedId = $pdo->lastInsertId();
        }

        // if id was auto-incremented, set entity id to new one in data source
        if (!$entity->hasCompositeKey()) {
            $entity->setId($insertedId);
        }

        return $entity->getId();
    }


    protected static function getParentClasses(): array
    {
        $currentEntityClass = static::getEntityClass();
        $classes = [];
        while ($currentEntityClass != 'BaseEntity') {
            $classes[] = $currentEntityClass;
            $currentEntityClass = get_parent_class($currentEntityClass);
        }

        return array_reverse($classes);
    }

    protected static function hasParentEntity($entityClass): bool
    {
        return get_parent_class($entityClass) != "BaseEntity";
    }


    /**
     * getColumnNames
     * get an array of column names, in the same order as they appear in the database schema
     * 
     * @param  bool $includePk include primary key in result?
     * @return void
     */
    public static function getColumnNames(bool $includePk = true): array
    {
        if (!isset(self::$cachedData['columnNames'][get_called_class()])) {
            $pdo = DatabaseUtil::getConnection();
            $q = $pdo->prepare("DESCRIBE " . static::getTableName());

            $q->execute();
            self::$cachedData['columnNames'][get_called_class()]
                = $q->fetchAll(PDO::FETCH_COLUMN);
        }

        $names = self::$cachedData['columnNames'][get_called_class()];

        if (!$includePk) {
            // Get index(es) of primary key(s) in table schema (usually but not always first)
            if (!is_array(static::getPkColumnName())) {
                $keys = [static::getPkColumnName()];
            } else {
                $keys = static::getPkColumnName();
            }
            foreach ($keys as $key) {
                $primaryKeyIndex = array_search($key, $names);
                if ($primaryKeyIndex !== false) {
                    unset($names[$primaryKeyIndex]); // unset it
                }
            }
            $names = array_values($names); // re-establish indexes starting from 0
        }

        return $names;
    }

    /**
     * getColumnNames
     * get an array of column names, in the same order as they appear in the database schema
     * 
     * @param  bool $includePk include primary key in result?
     * @return void
     */
    public static function getColumnDefaults(bool $includePk = true): array
    {
        if (!isset(self::$cachedData['columnDefaults'][get_called_class()])) {
            $pdo = DatabaseUtil::getConnection();
            $sql = "SELECT COLUMN_NAME, COLUMN_DEFAULT
            FROM information_schema.columns
            WHERE TABLE_NAME = '" . static::getTableName() ."'
            AND COLUMN_DEFAULT != 'NULL'";
            $q = $pdo->prepare($sql);
            $q->execute();
            self::$cachedData['columnDefaults'][get_called_class()]
                = $q->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        return self::$cachedData['columnDefaults'][get_called_class()];
    }
    
    /**
     * getManyToMany
     * find related entities through a join table
     * @param  mixed $startEntity for which we're looking for relations
     * @param  string  $joinEntityClass join table entity class
     * @param  string  $endEntityClass that are related to the stating entity through the join class
     * @param  array $options query options, ie: [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return array of related entities
     */
    public static function findManyToMany($startEntity, string $joinEntityClass, string $endEntityClass, $options = null): array
    {
        static::initializeQueryOptions($options);

        $startDao = get_class($startEntity)::getDaoClass();
        $joinDao = $joinEntityClass::getDaoClass();
        $endDao = $endEntityClass::getDaoClass();

        // create standard many-to-many join sql
        $sql = "SELECT e.* FROM " . $joinDao::getTableName() . " j" .
            " JOIN " . $endDao::getTableName() . " e" .
            " ON e." . $endDao::getPkColumnName() . " = j." . $endDao::getPkColumnName() .
            " AND  j." . $startDao::getPkColumnName() . " = ? ";

        $values = [$startEntity->getId()];

        // apply request options, starting from the existing join sql and id value 
        $req = $endDao::buildRequest($options, $sql, $values);

        return $endDao::getEntitiesFromRequest($req, $options);
    }

    /**
     * getEntitiesFromRequest
     * builds an array of entities from a request's result set
     * 
     * @param  mixed $req request object to get entities from
     * @param  mixed $options request options 
     * @return array of fetched entities
     */
    protected static function getEntitiesFromRequest($req, $options): array
    {
        $entities = [];
        // set entity properties using fetched values
        while (($entity = static::fetchEntity($req, $options)) !== false) {
            if ($entity != null) { // entity might have a parent that didn't sastisfy remaining query options
                if (isset($options["INDEXBY"])) {
                    // if options specify a getter to index by
                    $key = EntityUtil::get($entity, $options["INDEXBY"]);
                    $entities[$key] = $entity;
                } else {
                    $entities[] = $entity;
                }
            }
        };

        return $entities;
    }
}
