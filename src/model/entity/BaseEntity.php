<?php


/**
 * BaseEntity
 */
class BaseEntity{
    // Original ID of entity when fetched from data source.
    // Used if we need to update it after changing its id property
    protected $originalIds=[];

    /**
     * getDaoClass
     * get the DAO class that corresponds to the current instance
     * @return String the DAO class, ie "RecipeDao"
     */
    public static function getDaoClass(): String{
        return get_called_class() . "Dao";
    }

    /**
     * getRelatedEntities
     * Get an array of entities that are joined to the current instance by a foreign key
     * 
     * @param  string $relatedEntityClass Class of the related entity to look for
     * @param  array $options
     * @return array of related entities
     */
    public function getRelatedEntities(string $relatedEntityClass, array $options=[]): array
    {
        // find dao class of the related entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        $thisPrimaryKeyName = static::getDaoClass()::getPkColumnName();
  
        $options[ $thisPrimaryKeyName ] = $this->getId();

        return $relatedClassDao::findAll($options);
    }

        
    /**
     * getRelatedEntity
     * Get an entity that is joined to the current instance by a foreign key
     * 
     * @param  string $relatedEntityClass Class of the related entity to look for
     * @param  array $options
     * @return mixed related entity, or null if none exists
     */
    public function getRelatedEntity(string $relatedEntityClass, array $options=[])
    {
        // find column name of the related entity's primary key
        $relatedClassPrimaryKey = $relatedEntityClass::getDaoClass()::getPkColumnName();

        $relatedDao = $relatedEntityClass::getDaoClass();

        // If foreign key is in current instance
        if (  property_exists($this, $relatedClassPrimaryKey) ){
            $options[ $relatedDao::getPkColumnName() ] = EntityUtil::get($this, $relatedClassPrimaryKey);
        } else { // If foreign key is in related object
            $options[ static::getDaoClass()::getPkColumnName() ] = $this->getId();
        }

        return $relatedDao::findOne($options);
    }



  
    /**
     * setRelatedEntity
     * sets the current instance's foreign key parameter to that of the related entity's primary key
     * @param  mixed $relatedEntity to link to current instance
     * @return void
     */
    public function setRelatedEntity($relatedEntity)
    {
        // find dao class of the joined entity
        $relatedClassDao = get_class($relatedEntity)::getDaoClass();

        // find column name of the joined entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::getForeignKeyName();


        // If foreign key is in current instance
        if (  property_exists($this, $relatedClassPrimaryKey) ){
            EntityUtil::set(
                $this,
                $relatedClassPrimaryKey,
                $relatedEntity->getId()
            );
        } else { // If foreign key is in related object
            EntityUtil::set(
                $relatedEntity,
                static::getDaoClass()::getForeignKeyName(),
                $this->getId()
            );
        }
    }


    /**
     * getId
     * get the primary key value for the current instance
     * @return mixed
     */
    public function getId(){
        if ( !$this->hasCompositeKey() ){
            $idColumnName = static::getDaoClass()::getPkColumnName();
            $result = EntityUtil::get($this, $idColumnName);
        } else {
            $result = [];
            foreach( static::getDaoClass()::getPkColumnName() as $pkColumn ){
                $result[$pkColumn] = EntityUtil::get($this, $pkColumn);
            }
        }
        return $result;
    }

    /**
     * getId
     * set the primary key value for the current instance
     * @return void
     */
    public function setId($id){
        if ( $this->hasCompositeKey() ){
            foreach ( $id as $pkColumnName=>$pkValue){
                EntityUtil::set($this,  $pkColumnName, $pkValue);
            }
        } else {
            $idColumnName = static::getDaoClass()::getPkColumnName();
            EntityUtil::set($this,  $idColumnName, $id);
        }
    }
    

     /**
     * getIndirectlyRelatedEntities
     * Get an array of entities that are joined to the current instance in a many-to-many relationship 
     * @param  string $relatedEntityClass indirectly related entity we're looking for
     * @param  string $joinClass entity that links the target related entity to the current instance (via a join table)
     * @param  array $options query options, ie: 'a' or [ 'articlePrice <=' => 12, 'flag' => 'a']
     * @return array of related entities
     * 
     */
    public function getIndirectlyRelatedEntities(string $relatedEntityClass, string $joinClass, $options= null): array
    {
        return self::getDaoClass()::findManyToMany($this,  $joinClass , $relatedEntityClass, $options);
    }


        
    /**
     * getChildEntity
     * Get a Entity of the subclass speficied in parameters and whose table inherits current entity's table
     * @param  mixed $childEntityClass
     * @return mixed
     */
    public function getChildEntity(string $childEntityClass){
        return $childEntityClass::getDaoClass()::findById($this->getId());
    }

        
    /**
     * makeChildEntity
     * Creates a child entity (whose table inherits current entity's table) of the current instance
     * @param  mixed $childEntityClass
     */
    public function makeChildEntity(string $childEntityClass){
        if ( $this->getChildEntity($childEntityClass) == null ) {
            $child = new $childEntityClass;
            $child->setId($this->getId());
            
            $childEntityClass::getDaoClass()::save($child);
        }

        return $this;
    }

        
    /**
     * equals
     * Two entities are considered equal if of is a subclass of the other or the same class, and if they have the same primary keys
     * @param  mixed $other
     */
    public function equals( $other ){
        return $other != null
            &&     is_a($this,get_class($other)) // $this must either be class/sublass of $other
                || is_a($other,get_class($this)) // or vice-versa
            && $this->hasSamePrimaryKey($other);
    }
    
    /**
     * hasPrimaryKey
     * Is the current entity's primary key initialized?
     * @return void
     */
    public function hasPrimaryKey(){
        if ( !$this->hasCompositeKey() ){
            $keys = [$this->getId()];
        } else {
            $keys = $this->getId();
        }

        $hasPk = true;

        foreach( $keys as $key ){
            $hasPk &= ($key != null);
        }

        return $hasPk;
    }
    
    /**
     * hasCompositeKey
     * Does the currrent entity have a composite primary key?
     */
    public function hasCompositeKey(){
        return is_array(static::getDaoClass()::getPkColumnName());
    }

    
    /**
     * hasSamePrimaryKey
     * Is the current entity's primary key identical to the primary key of another entity?
     * @param  mixed $other
     */
    public function hasSamePrimaryKey($other){
        if ( $this->hasCompositeKey() ){
            $samePk = true;

            if ( $other->hasCompositeKey() ){
                foreach( static::getDaoClass()::getPkColumnName() as $pkNameIndex=>$pkName){
                    // check if same pk column values
                    $samePk &= (
                        $this->getId()[$pkNameIndex] ?? null
                    === ( $other->getId()[$pkNameIndex] ?? false )
                    );
                }
            }
        } else {
            $samePk = $this->getId() == $other->getId();
        }

        return $samePk;
    }
    
    /**
     * existsInDataSource
     * Is there a row in the data source that corresponds to the current entity?
     */
    public function existsInDataSource(){
        if ( !$this->hasCompositeKey()){
            $exists = !empty($this->getOriginalId());
        } else {
            $options = [];
            foreach ( static::getDaoClass()::getPkColumnName() as $pkName ){
                $options[$pkName] = EntityUtil::get($this, $pkName);
            }

            $exists = ( static::getDaoClass()::findOne($options) != null );
        }

        return $exists;
    }

    
    /**
     * getOriginalId
     * if the current entity was fetched from the data source, get the ID it had when it was instanciated
     * @param  mixed $columnName
     */
    public function getOriginalId($columnName = null)
    {
        if ( $columnName == null ){
            if ( $this->hasCompositeKey() ){
                $result = $this->originalIds;
            } else {
                $columnName = get_class($this)::getDaoClass()::getPkColumnName();

                $result = $this->originalIds[$columnName] ?? null;
            }
        } else {
            $result = $this->originalIds[$columnName];
        }
        return $result;
    }
    
    /**
     * setOriginalId
     * set an entity's original ID propery, which represents it's ID in the data source.
     * @param  mixed $value
     * @param  mixed $columnName
     */
    public function setOriginalId($value, $columnName = null )
    {
        if ( $columnName == null ){
            if ( $this->hasCompositeKey() ){
                $this->originalIds = $value;
            } else {
                $columnName = get_class($this)::getDaoClass()::getPkColumnName();
                $this->originalIds[$columnName] = $value;
            }
        } else {
            $this->originalIds[$columnName] = $value;
        }
    }
}