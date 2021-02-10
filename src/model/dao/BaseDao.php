<?php
class BaseDao
{
    protected static $cachedData=['columnNames'=>[]];

    public const FLAGS = ['active'  => 'a',
        'waiting' => 'w',
        'blocked' => 'b' ];
    
    /**
     * getTableName
     * get corresponding table name in data source, ie "recipe"
     * @return String table name in data source
     */
    public static function getTableName(): String{
        return strtolower(self::getEntityClass());
    }

    /**
     * getEntityClass
     * get corresponding entity class name in data source, ie "Recipe"
     * @return String entity class name in data source
     */
    public static function getEntityClass(): String{
        return substr(get_called_class(), 0, -3);
    }

    /**
     * getEntityClass
     * get primary key column of current table, ie "RecipeId"
     * @return String entity class name in data source
     */
    public static function getPkColumnName(): String{
        return 'id' . self::getEntityClass(); 
    }
    
    /**
     * initializeQueryOptions
     *
     * @param  mixed $options either a string (flag to look for), or an array of query options
     *                 ie: [ 'articlePrice >=' => 12, 'articleName' => 'Marlboro Lights']
     * @return void initialized array of options
     */
    protected static function initializeQueryOptions(&$options){
        // if null, return empty array
        if ( $options == null ){
            $options = [];
        } elseif (!is_array($options)) { 
            // if options is a simple string, assume we're looking for a flag 
            $options = ['flag'=>$options];
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
    protected static function buildRequest(array &$options, ?string $sql = null, array $values=[]): PDOStatement{
        $pdo = DatabaseUtil::getConnection();

        // if no starting sql specified, select all
        if ( $sql == null ) {
            $sql = "SELECT * FROM " . static::getTableName();
        }


        // $options may be in the form [ 'articlePrice <=' => 12,
        //                               'flag' => 'a',
        //                               'HOUR(creationDate) >=' => 9,
        //                               'HOUR(creationDate) <' => 17 ]
        // we must pick out the property name for later validation
        $conditions = [];
        array_walk ( $options, function($value, $key) use (&$conditions, &$options){
            if (preg_match(
                "/^(.*)(=|>|<|>=|<=|<>|!=|LIKE|NOT LIKE)$/", // look for an operator at the end of option key
                $key, $matches)
            ){
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
                $propertyKey, $matches)
            ) {
                // if so, actual property key is within the parentheses
                $propertyKey = trim($matches[1]);
            }

            // only add if it has an equivalent table column
            if (in_array($propertyKey, static::getColumnNames())) {
                $conditions[] = [ 'condition' => $condition, 'value' => $value ];
                // remove property from options in case we reuse the same options in parent entity query
                unset($options[$key]); 
            }
        } );

        // $translatedOptions is now in the form 
        // [ [ 'condition' => 'articlePrice <= ?',
        //     'value' => 12 ],
        //   [ 'condition' => 'flag = ?',
        //     'value' => 'a'] ]
        //   [ 'condition' => 'HOUR(creationDate) >= ?',
        //     'value' => 8] ]
        //   [ 'condition' => 'HOUR(creationDate) < ?',
        //     'value' => 15] ]

        foreach ($conditions as $i=>$optionParameters){
            if ( $i == array_key_first($conditions) ){
                $sql .= " WHERE ";
            }

            $sql .= $optionParameters['condition'];
            $values[] = $optionParameters['value'];

            // don't add another AND if we're done adding WHERE conditions
            if ( $i != array_key_last($conditions) ){
                $sql .= " AND ";
            }
        }


        if ( isset ($options['ORDER']) ){
            if (preg_match(
                "/^(.*) (DESC|ASC)$/", // look for an operator at the end of ORDER option
                $options['ORDER'], $matches)
            ) { // if order option in the form 'ORDER' => 'articlePrice ASC' or 'ORDER' => 'articlePrice DESC'
                $sql .= " ORDER BY {$matches[1]} {$matches[2]}";
            } else { // if order option in the form 'ORDER' => 'articlePrice', assume DESC
                $sql .= " ORDER BY {$options['ORDER']} DESC";
            }
            unset($options['ORDER']);
        } elseif (in_array(static::getPkColumnName(), static::getColumnNames())) {
            $sql .= " ORDER BY " . static::getPkColumnName() . " ASC";
        }

        if ( isset ($options['LIMIT']) ){
            $sql .= " LIMIT " . $options['LIMIT'];
            unset($options['LIMIT']);
        }

        if ( isset ($options['OFFSET']) ){
            $sql .= " OFFSET " . $options['OFFSET'];
            unset($options['OFFSET']);
        }

        $request = $pdo->prepare($sql);
        $request->execute($values);

        return $request;
    }

    /**
     * findOneBy
     * create and return an entity corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return mixed entity if found, null otherwise
     */
    public static function findOneBy(string $key, $value, $options=null)
    {
        static::initializeQueryOptions($options);

        $options[$key] = $value;

        return static::findAll($options)[0] ?? null;

        // $req = static::buildRequest($options);

        // $entity = self::fetchEntity($req, $options); // set entity properties using fetched values

        // return $entity ?? null; // fetchObject returns boolean false if no row found, whereas we want null
    }

    /**
     * findById
     * create and return an entity corresponding to a given id
     * @param  mixed $id primary key
     * @return mixed if found, null otherwise
     */
    public static function findById($id, $options=null){
        static::initializeQueryOptions($options);

        $options[self::getPkColumnName()] = $id;

        return static::findAll($options)[0] ?? null;
    }

    /**
     * fetchEntity
     * transforms a request result row into an entity. 
     * if entity is based on an inherited table, loops through all parent tables to populate entity properties
     * @param  mixed $req
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return void
     */
    protected static function fetchEntity(&$req, array $queryOptions){
        // start by initializing entity as a fetched object of current request
        $entity = $req->fetchObject(static::getEntityClass()); 
        $currentClass = get_parent_class(static::getEntityClass());

        // loop through all parent entities
        while ( $entity != null && $currentClass != 'BaseEntity' ){
            $currentDao = $currentClass::getDaoClass();
            $currentQueryOptions = $queryOptions;

            $currentQueryOptions[$currentDao::getPkColumnName()] = $entity->getId();

            $currentRequest = $currentDao::buildRequest($currentQueryOptions);
           
            // get row data as associative array, set initial entity's properties to that of current child's properties
            $rowData = $currentRequest->fetch(PDO::FETCH_ASSOC);

            if ( $rowData === false ){ // if a query option constraint fails (ie blocked flag in a parent)
                $entity = null; // will return null
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
    public static function findAll($options=null): array
    {
        static::initializeQueryOptions($options);

        $req = static::buildRequest($options);

        $entities = [];
        // set entity properties using fetched values
        while ( ($entity = self::fetchEntity($req, $options)) !== false ) { 
            if ($entity != null){ // entity might have a parent with a blocked flag
                $entities[] = $entity;
            }
        };
        return $entities;
    }


    /**
     * findOneBy
     * create and return an array of entities corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @return array of entities
     */
    public static function findAllBy(String $key, $value, $options=null)
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
    public static function delete($entity) {
        $pdo = DatabaseUtil::getConnection();
        $sql = "DELETE FROM " . self::getTableName() . " WHERE " . self::getPkColumnName() . " = ?";
        $q = $pdo->prepare($sql);
        $q->execute([EntityUtil::get($entity, self::getPkColumnName()) ?? null]); // if entity doesn't exist, null instead of pk
    }

    /**
     * saveOrUpdate
     * Save an entity to data source if it exists, or insert a new row if it doesn't
     * @param  mixed $entity
     * @return void
     */
    public static function saveOrUpdate(?BaseEntity &$entity){
        $pdo = DatabaseUtil::getConnection();
        if(!empty( EntityUtil::get($entity, self::getPkColumnName()) )){
            self::update($entity);  // if entity has a primary key set, it already exists in data source
        }
        else {
            self::save($entity); // If no primary key set, insert new row
        }
    }
    
    /**
     * update
     * update the table row that corresponds to an entity
     * @param  BaseEntity $entity to update in table
     * @return void
     */
    public static function update(?BaseEntity &$entity) {
        // Loop through inherited tables (from parent to child), updating the relevant entity properties
        foreach ( self::getParentClasses() as $currentClass ) { 
            $pdo = DatabaseUtil::getConnection();
            $currentDao = $currentClass::getDaoClass();

            $columnNames = $currentDao::getColumnNames(false);

            // update conditions are in the form "COLUMN_NAME = ?, COLUMN_NAME2 = ?, ..."
            $conditions = array_map(function($columnName) { return "$columnName = ?"; }, $columnNames);

            $sql = "UPDATE " . $currentDao::getTableName() . " SET " . implode(',', $conditions) . " WHERE " . $currentDao::getPkColumnName() . " = ?";

            $q = $pdo->prepare($sql);

            $values = array_map( // Create a new array out of the column names
                function($columnName) use ($entity) {
                    return EntityUtil::get($entity,$columnName); // Each column name corresponds to an entity getter 
                },
                $columnNames
            );
            // Add primary key to list of values
            $values[] = $entity->getId();

            $q->execute($values);
        }
    }
    

    
    /**
     * save
     * insert a new row into data source that corresponds to an entity
     * @param  BaseEntity $entity to base new row on
     * @return int inserted entity's PK
     */
    public static function save(?BaseEntity &$entity) {
        $insertedId = null;
        // Loop through inherited tables (from parent to child), inserting the relevant entity properties
        foreach ( self::getParentClasses() as $currentClass ) { 
            $pdo = DatabaseUtil::getConnection();
            $currentDao = $currentClass::getDaoClass();
                  
            if($currentDao::findById($entity->getId()) != null){
                $insertedId = $entity->getId();
                continue;
            }

            $columnNames = $currentDao::getColumnNames(false); // get column names for current table

            // populate values with the entity properties that correspond to the column names
            $values = array_map(function($columnName) use ($entity) { return EntityUtil::get($entity,$columnName); }, $columnNames);

            // if we're dealing with an inherited table, we must insert parent id explicitly to child table
            if (self::hasParentEntity($currentClass)){
                $columnNames[] = $currentDao::getPkColumnName();
                
                $values[] = $insertedId;
                $entity->setId($insertedId);
            }
            // Need a list of question marks of same size as the list of column names
            $questionMarks = array_map(function($columnName) { return '?'; }, $columnNames);

            $sql = "INSERT INTO " . $currentDao::getTableName() . " (" . implode(',', $columnNames) . ") 
            values(" . implode(',', $questionMarks) . ")";

            $q = $pdo->prepare($sql);
            
            $q->execute($values);  
  

            $insertedId = $pdo->lastInsertId();
        }
        return $entity->getId(); // Last inserted ID is entity's id
    }


    protected static function getParentClasses(): Array{
        $currentEntityClass = self::getEntityClass();
        $classes = [];
        while ($currentEntityClass != 'BaseEntity' ) {
            $classes[] = $currentEntityClass;
            $currentEntityClass = get_parent_class($currentEntityClass);
        }

        return array_reverse($classes);
    }

    protected static function hasParentEntity($entityClass): bool{
        return get_parent_class($entityClass) != "BaseEntity";
    }


    /**
     * getColumnNames
     * get an array of column names, in the same order as they appear in the database schema
     * 
     * @param  bool $includePk include primary key in result?
     * @return void
     */
    public static function getColumnNames(bool $includePk=true): Array{
        if ( !isset(static::$cachedData['columnNames'][get_called_class()]) ){
            $pdo = DatabaseUtil::getConnection();
            $q = $pdo->prepare("DESCRIBE " . self::getTableName());

            $q->execute();
            static::$cachedData['columnNames'][get_called_class()]
                = $q->fetchAll(PDO::FETCH_COLUMN);
        }

        $names = static::$cachedData['columnNames'][get_called_class()];

        if (!$includePk){
            // Get index of primary key in table schema (usually but not always first)
            $primaryKeyIndex = array_search(self::getPkColumnName(), $names);
            unset($names[$primaryKeyIndex]); // unset it
            $names = array_values($names); // re-establish indexes starting from 0
        }

        return $names;
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
    public static function findManyToMany($startEntity, string $joinEntityClass, string $endEntityClass, $options): array{
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

        // build request, starting from the existing join sql and values 
        $req = $endDao::buildRequest($options, $sql, $values);

        $endEntities = [];
        while ( ($entity = $endDao::fetchEntity($req, $options)) !== false ) { // set entity properties to fetched column values
            if ($entity != null){ // entity might have a parent with a blocked flag
                $endEntities[] = $entity;
            }
        }

        return $endEntities;
    }
}
