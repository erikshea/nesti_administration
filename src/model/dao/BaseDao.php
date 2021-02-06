<?php
class BaseDao
{
    protected static $cachedData=['columnNames'=>[]];

    public const FLAGS = ['active'  => 'a',
        'waiting' => 'w',
        'blocked' => 'b' ];

    public static function getTableName(): String{
        return strtolower(self::getEntityClass());
    }

    public static function getEntityClass(): String{
        return substr(get_called_class(), 0, -3);
    }

    public static function getPkColumnName(): String{
        return 'id' . self::getEntityClass();
    }

    /**
     * findOneBy
     * create and return an entity corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @return mixed if found, null otherwise
     */
    public static function findOneBy(String $key, $value, $flag=null)
    {
        $pdo = DatabaseUtil::connect();
        $sql = "SELECT * FROM " . self::getTableName() . " WHERE $key = ?";
        $values = [$value];

        if ( $flag != null  && in_array('flag', self::getColumnNames()) ){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute($values);

        $entity = self::fetchEntity($req, $flag); // set entity properties using fetched values

        return $entity ?? null; // fetchObject returns boolean false if no row found, whereas we want null
    }
    
    /**
     * fetchEntity
     * transforms a request result row into an entity. 
     * if entity is based on an inherited table, loops through all parent tables to populate entity properties
     * @param  mixed $req
     * @param  mixed $flag
     * @return void
     */
    protected static function fetchEntity(&$req, $flag){
        // start by initializing entity as a fetched object of current request
        $entity = $req->fetchObject(self::getEntityClass()); 
        $currentClass = get_parent_class(self::getEntityClass());

        // loop through all parent classes
        while ( $entity != null && $currentClass != 'BaseEntity' ){
            $currentDao = $currentClass::getDaoClass();

            $pdo = DatabaseUtil::connect();
            $sql = "SELECT * FROM " . $currentDao::getTableName() . " WHERE " . $currentDao::getPkColumnName() . " = ?";
            $values = [$entity->getId()];
   
            if ( $flag != null  && in_array('flag', $currentDao::getColumnNames()) ){
 
                $sql .= " AND flag = ?";
                $values[] = $flag;
            }
            $currentRequest = $pdo->prepare($sql);
            $currentRequest->execute($values);
           
            $rowData = $currentRequest->fetch(PDO::FETCH_ASSOC);

            if ( $rowData === false ){ // if flag constraint fails
                $entity = null;
                break;
            } else {
                EntityUtil::setFromArray($entity, $rowData);
            }

            // get row data as associative array, set initial entity's properties to that of current child's properties
            
            $currentClass = get_parent_class($currentClass);
        }

        return $entity;
    }


    /**
     * findById
     * create and return an entity corresponding to a given id
     * @param  mixed $id primary key
     * @return mixed if found, null otherwise
     */
    public static function findById($id, $flag=null){
        return self::findOneBy(self::getPkColumnName(), $id, $flag);
    }

    /**
     * findAll
     * fetch all table rows, create and return matching entitiess
     * @return Array of entities (or empty if no rows in table)
     */
    public static function findAll($flag=null): array
    {
        $pdo = DatabaseUtil::connect();
        $sql = "SELECT * FROM " . self::getTableName() . " ORDER BY " . self::getPkColumnName() . " DESC";
        
        $values = [];

        if ( $flag != null && in_array('flag', self::getColumnNames()) ){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
 
        $req->execute($values);

        $entities = [];
        while ( ($entity = self::fetchEntity($req, $flag)) !== false ) { // set entity properties using fetched values
            if ($entity != null){ // entity might have a parent with a blocked flag
                $entities[] = $entity;
            }
        };
        return $entities;
    }


    /**
     * findOneBy
     * create and return a Objet corresponding to a given field name, and it's searched value
     * @param  String $key field name
     * @param  mixed $value to search
     * @return Objet if found, null otherwise
     */
    public static function findAllBy(String $key, $value, $flag=null)
    {
        $pdo = DatabaseUtil::connect();
        $sql = "SELECT * FROM " . self::getTableName() . " WHERE $key = ?";
        $values = [$value];

        if ( $flag != null && in_array('flag', self::getColumnNames()) ){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute($values);

        $entities = [];
        while ( ($entity = self::fetchEntity($req, $flag)) !== false ) { // set entity properties to fetched column values
            if ($entity != null){ // entity might have a parent with a blocked flag
                $entities[] = $entity;
            }
        };
        return $entities;
    }

    /**
     * saveOrUpdate
     * Save an entity to data source if it exists, or insert a new row if it doesn't
     * @param  mixed $entity
     * @return void
     */
    public static function saveOrUpdate(?BaseEntity &$entity){
        $pdo = DatabaseUtil::connect();
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
            $pdo = DatabaseUtil::connect();
            $currentDao = $currentClass::getDaoClass();

            $columnNames = $currentDao::getColumnNames();

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
            $pdo = DatabaseUtil::connect();
            $currentDao = $currentClass::getDaoClass();
            
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
    public static function getColumnNames(bool $includePk=false): Array{
        if ( !isset(static::$cachedData['columnNames'][get_called_class()]) ){
            $pdo = DatabaseUtil::connect();
            $q = $pdo->prepare("DESCRIBE " . self::getTableName());

            $q->execute();
            $names = $q->fetchAll(PDO::FETCH_COLUMN);

            static::$cachedData['columnNames'][get_called_class()] =  $names;
        }
        $names = static::$cachedData['columnNames'][get_called_class()];

        if (!$includePk){
            // Get index of primary key in table schema (usually but not always first)
            $primaryKeyIndex = array_search(self::getPkColumnName(), $names);
            unset($names[$primaryKeyIndex]); // unset it
        }
        // we must re-establish indexes starting from 0 in case we removed primary key
        return array_values($names);
    }

    
    /**
     * delete
     * remove row in data source that corresponds to an Entity
     * @param  mixed $entity entity to remove from data source
     * @return void
     */
    public static function delete($entity) {
        $pdo = DatabaseUtil::connect();
        $sql = "DELETE FROM " . self::getTableName() . " WHERE " . self::getPkColumnName() . " = ?";
        $q = $pdo->prepare($sql);
        $q->execute([EntityUtil::get($entity, self::getPkColumnName()) ?? null]); // if entity doesn't exist, null instead of pk
    }

    public static function getManyToMany($startEntity, $joinEntityClass, $endEntityClass, $flag=null){
        $start = get_class($startEntity)::getDaoClass();
        $join = $joinEntityClass::getDaoClass();
        $end = $endEntityClass::getDaoClass();

        $pdo = DatabaseUtil::connect();
        $sql = "SELECT e.* FROM " . $join::getTableName() . " j" .
            " JOIN " . $end::getTableName() . " e" .
                " ON e." . $end::getPkColumnName() . " = j." . $end::getPkColumnName() .
                " AND  j." . $start::getPkColumnName() . " = ? ";
        
        $values = [$startEntity->getId()];
        if ( $flag != null ){
            $sql .= " WHERE e.flag = ?" ;
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute($values);


        $endEntities = [];
        while ( ($entity = self::fetchEntity($req, $flag)) !== false ) { // set entity properties to fetched column values
            if ($entity != null){ // entity might have a parent with a blocked flag
                $endEntities[] = $entity;
            }
        }

        return $endEntities;
    }
}
