<?php
class BaseEntity{
    
    /**
     * getDaoClass
     * get the DAO class that corresponds to the current instance
     * @return String the DAO class, ie "RecipeDao"
     */
    public static function getDaoClass(): String{
        return self::class . "Dao";
    }

    /**
     * findOneToMany
     * Get an array of entities that is joined in a one-to-many relationship to the current instance
     * 
     * @param  mixed $joinedEntityClass Class of the joined entity to look for
     * @return array of related entities
     */
    protected function getRelatedEntities(String $relatedEntityClass): array
    {
        // find dao class of the joined entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the joined entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::$entityPrimaryKey;

        return $relatedClassDao::findAllBy(
            // joined entity's primary key is the same one as starting entity's corresponding foreign key 
            $relatedClassPrimaryKey,
            EntityUtil::get($this, $relatedClassPrimaryKey)
        );
    }

        
    /**
     * findOneToOne
     * Get an entity that is joined in a one-to-one relationship to the current instance
     * 
     * @param  mixed $joinedEntityClass Class of the joined entity to look for
     * @return mixed related entity, or null if none exists
     */
    protected function getRelatedEntity(String $relatedEntityClass)
    {
        // find dao class of the joined entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the joined entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::$entityPrimaryKey;

        return $relatedClassDao::findById(
            // joined entity's primary key is the same one as starting entity's corresponding foreign key 
            EntityUtil::get($this, $relatedClassPrimaryKey) 
        );
    }


  
    /**
     * setRelatedEntity
     * sets the current instance's foreign key parameter to that of the related entity's primary key
     * @param  mixed $relatedEntity to link to current instance
     * @return void
     */
    protected function setRelatedEntity($relatedEntity)
    {
        // find dao class of the joined entity
        $relatedClassDao = $relatedEntity->getClass()::getDaoClass();

        // find column name of the joined entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::$entityPrimaryKey;

        EntityUtil::set($this, $relatedClassPrimaryKey, $relatedEntity->getId());

        self::getDaoClass()::saveOrUpdate($this);
    }


    
    /**
     * getId
     * get the primary key value for the current instance
     * @return void
     */
    public function getId(){
        $idColumnName = self::getDaoClass()::getEntityPrimaryKey();
        return EntityUtil::get($this, $idColumnName);
    }
}