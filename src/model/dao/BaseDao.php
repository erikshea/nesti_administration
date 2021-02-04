<?php

SiteUtil::require('util/EntityUtil.php');

class BaseDao
{
    public const FLAGS = ['active'  => 'a',
        'waiting' => 'w',
        'deleted' => 'b' ];

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
     * @return Objet if found, null otherwise
     */
    public static function findOneBy(String $key, $value, $flag=null)
    {
        $pdo = DatabaseUtil::connect();
        $sql = "SELECT * FROM " . self::getTableName() . " WHERE $key = ?";
        $values = [$value];

        if ( $flag != null){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute($values);
        $entity = $req->fetchObject(self::getEntityClass()); // set entity properties using fetched values

        return $entity ?? null; // fetchObject returns boolean false if no row found, whereas we want null
    }

    /**
     * findById
     * create and return an entity corresponding to a given id
     * @param  mixed $id primary key
     * @return Objet if found, null otherwise
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

        if ( $flag != null){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute($values);

        $entities = [];
        while ($entity = $req->fetchObject(self::getEntityClass())) { // set entity properties using fetched values
            $entities[] = $entity;
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

        if ( $flag != null){
            $sql .= " AND flag = ?";
            $values[] = $flag;
        }

        $req = $pdo->prepare($sql);
        $req->execute([$values]);

        $entities = [];
        while ($entity = $req->fetchObject(self::getEntityClass())) { // set entity properties to fetched column values
            $entities[] = $entity;
        };
        return $entities;
    }

    /**
     * saveOrUpdate
     * Save an entity to data source if it exists, or insert a new row if it doesn't
     * @param  mixed $entity
     * @return void
     */
    public static function saveOrUpdate(&$entity){
        $pdo = DatabaseUtil::connect();
        if(!empty( EntityUtil::get($entity, self::getPkColumnName()) )){
            self::update($entity);  // if entity has a primary key set, it already exists in data source
        }
        else {
            $pk = self::save($entity); // If no primary key set, insert new row
            EntityUtil::set($entity, self::getPkColumnName(), $pk);
        }
    }
    
    /**
     * update
     * update the table row that corresponds to an entity
     * @param  mixed $entity to update in table
     * @return void
     */
    public static function update($entity) {
        $pdo = DatabaseUtil::connect();

        $columnNames = self::getColumnNames();

        // update conditions are in the form "COLUMN_NAME = ?, COLUMN_NAME2 = ?, ..."
        $conditions = array_map(function($columnName) { return "$columnName = ?"; }, $columnNames);

        $sql = "UPDATE " . self::getTableName() . " SET " . implode(',', $conditions) . " WHERE " . self::getPkColumnName() . " = ?";

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
    

    
    /**
     * save
     * insert a new row into data source that corresponds to an entity
     * @param  mixed $entity to base new row on
     * @return int inserted entity's PK
     */
    public static function save($entity): int {
        $pdo = DatabaseUtil::connect();

        $columnNames = self::getColumnNames(false);

        // Need a list of question marks of same size as the list of column names
        $questionMarks = array_map(function($columnName) { return '?'; }, $columnNames);

        $sql = "INSERT INTO " . self::getTableName() . " (" . implode(',', $columnNames) . ") 
        values(" . implode(',', $questionMarks) . ")";
        FormatUtil::dump($sql);
        $q = $pdo->prepare($sql);

        $values = array_map(function($columnName) use ($entity) { return EntityUtil::get($entity,$columnName); }, $columnNames);
        $q->execute($values);
        
        return $pdo->lastInsertId(); // Last inserted ID is entity's id
    }

    
    /**
     * getColumnNames
     * get an array of column names, in the same order as they appear in the database schema
     * 
     * @param  bool $includePk include primary key in result?
     * @return void
     */
    public static function getColumnNames(bool $includePk=false): Array{
        $pdo = DatabaseUtil::connect();
        $q = $pdo->prepare("DESCRIBE " . self::getTableName());
        $q->execute();
        $columnNames = $q->fetchAll(PDO::FETCH_COLUMN);

        if (!$includePk){
            // Get index of primary key in table schema (usually but not always first)
            $primaryKeyIndex = array_search(self::getPkColumnName(), $columnNames);
            unset($columnNames[$primaryKeyIndex]); // unset it
        }

        // we must re-establish indexes starting from 0 in case we removed primary key
        return array_values($columnNames);
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
        $start = $startEntity::class::getDaoClass();
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
        $req->execute([$values]);

        $endEntities = [];
        while ($entity = $req->fetchObject($end::getEntityClass())) { // set entity properties to fetched column values
            $endEntities[] = $entity;
        }

        return $endEntities;
    }
}
