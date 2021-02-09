<?php
class BaseEntity{
    
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
     * @param  mixed $relatedEntityClass Class of the related entity to look for
     * @return array of related entities
     */
    public function getRelatedEntities(String $relatedEntityClass, $flag=null): array
    {
        // find dao class of the related entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the related entity's primary key
        $thisPrimaryKeyName = static::getDaoClass()::getPkColumnName();

        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();
  
        return $relatedClassDao::findAllBy(
            // joined entity's foreign key name is the same as starting entity's primary key name 
            $thisPrimaryKeyName,
            $this->getId(),
            $flag
        );
    }

        
    /**
     * getRelatedEntity
     * Get an entity that is joined to the current instance by a foreign key
     * 
     * @param  mixed $relatedEntityClass Class of the related entity to look for
     * @return mixed related entity, or null if none exists
     */
    public function getRelatedEntity(String $relatedEntityClass, $flag=null): ?BaseEntity
    {
        // find dao class of the related entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the related entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();

        // If foreign key is in current instance
        if (  property_exists($this, $relatedClassPrimaryKey) ){
            $relatedEntity = $relatedClassDao::findById(
                // joined entity's primary key name is the same as starting entity's corresponding foreign key 
                EntityUtil::get($this, $relatedClassPrimaryKey) ,
                $flag
            );
        } else { // If foreign key is in related object
            $relatedEntity = static::getDaoClass()::findOneBy(
                // joined entity's foreign key name is the same as starting entity's primary key
                static::getDaoClass()::getPkColumnName(),
                $this->getId(),
                $flag
            );
        }

        return $relatedEntity;
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
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();


        // If foreign key is in current instance
        if (  property_exists($this, $relatedClassPrimaryKey) ){
            EntityUtil::set(
                $this,
                $relatedClassPrimaryKey,
                $relatedEntity->getId()
            );
            static::getDaoClass()::saveOrUpdate($this);
        } else { // If foreign key is in related object
            EntityUtil::set(
                $relatedEntity,
                static::getDaoClass()::getPkColumnName(),
                $this->getId()
            );
            $relatedClassDao::saveOrUpdate($relatedEntity);
        }
    }


    
    /**
     * getId
     * get the primary key value for the current instance
     * @return void
     */
    public function getId(){
        $idColumnName = static::getDaoClass()::getPkColumnName();
        return EntityUtil::get($this, $idColumnName);
    }

    /**
     * getId
     * get the primary key value for the current instance
     * @return void
     */
    public function setId($id){
        $idColumnName = static::getDaoClass()::getPkColumnName();
        EntityUtil::set($this,  $idColumnName, $id);
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
}