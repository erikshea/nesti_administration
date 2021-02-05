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
     * Get an array of entities that are joined to the current instance in a one-to-many relationship 
     * 
     * @param  mixed $relatedEntityClass Class of the related entity to look for
     * @return array of related entities
     */
    protected function getRelatedEntities(String $relatedEntityClass, $flag=null): array
    {
        // find dao class of the related entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the related entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();

        return $relatedClassDao::findAllBy(
            // joined entity's primary key is the same as starting entity's corresponding foreign key 
            $relatedClassPrimaryKey,
            EntityUtil::get($this, $relatedClassPrimaryKey),
            $flag
        );
    }

        
    /**
     * getRelatedEntity
     * Get an entity that is joined to the current instance in a one-to-one relationship 
     * 
     * @param  mixed $relatedEntityClass Class of the related entity to look for
     * @return mixed related entity, or null if none exists
     */
    protected function getRelatedEntity(String $relatedEntityClass, $flag=null)
    {
        // find dao class of the related entity
        $relatedClassDao = $relatedEntityClass::getDaoClass();

        // find column name of the related entity's primary key
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();

        return $relatedClassDao::findById(
            // joined entity's primary key is the same as starting entity's corresponding foreign key 
            EntityUtil::get($this, $relatedClassPrimaryKey) ,
            $flag
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
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();

        EntityUtil::set($this, $relatedClassPrimaryKey, $relatedEntity->getId());

        self::getDaoClass()::saveOrUpdate($this);
    }


    
    /**
     * getId
     * get the primary key value for the current instance
     * @return void
     */
    public function getId(){
        $idColumnName = self::getDaoClass()::getPkColumnName();
        return EntityUtil::get($this, $idColumnName);
    }



     /**
     * getIndirectlyRelatedEntities
     * Get an array of entities that are joined to the current instance in a many-to-many relationship 
     * 
     * @param  mixed $joinedEntityClass Class of the joined entity to look for
     * @return array of related entities
     */
    protected function getIndirectlyRelatedEntities(String $relatedEntityClass, String $joinClass, $flag = null): array
    {
        return self::getDaoClass()::getManyToMany($this,  $joinClass , $relatedEntityClass);
    }
}