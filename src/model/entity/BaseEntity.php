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
    public function getRelatedEntities(String $relatedEntityClass, $options=[]): array
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
     * @param  mixed $relatedEntityClass Class of the related entity to look for
     * @return mixed related entity, or null if none exists
     */
    public function getRelatedEntity(String $relatedEntityClass, $options=[]): ?BaseEntity
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
        $relatedClassPrimaryKey = $relatedClassDao::getPkColumnName();


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
                static::getDaoClass()::getPkColumnName(),
                $this->getId()
            );
        }
    }


    
    /**
     * getId
     * get the primary key value for the current instance
     * @return void
     */
    public function getId(){
        if ( !$this->hasCompositeKey() ){
            $idColumnName = static::getDaoClass()::getPkColumnName();
            $result = EntityUtil::get($this, $idColumnName);
        } else {
            $result = [];
            foreach( static::getDaoClass()::getPkColumnName() as $pkColumn ){
                $result[] = EntityUtil::get($this, $pkColumn);
            }
        }
        return $result;
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


    public function getChildEntity(string $childEntityClass){
        return $childEntityClass::getDaoClass()::findById($this->getId());
    }

    public function makeChildEntity(string $childEntityClass){
        if ( $this->getChildEntity($childEntityClass) == null ) {
            $child = new $childEntityClass;
            $child->setId($this->getId());
            FormatUtil::dump($child);
            $childEntityClass::getDaoClass()::save($child);
        }

        return $this;
    }

    public function equals( $other ){
        return $other != null
            &&     is_a($this,get_class($other)) // $this must either be class/sublass of $other
                || is_a($other,get_class($this)) // or vice-versa
            && $this->hasSamePrimaryKey($other);
    }

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

    public function hasCompositeKey(){
        return is_array(static::getDaoClass()::getPkColumnName());
    }


    public function hasSamePrimaryKey($other){
        $otherDao = get_class($other)::getDaoClass();

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

    public function existsInDataSource(){
        if ( !$this->hasCompositeKey()){
            $exists = $this->hasPrimaryKey();
        } else {
            $options = [];
            foreach ( static::getDaoClass()::getPkColumnName() as $pkName ){
                $options[$pkName] = EntityUtil::get($this, $pkName);
            }

            $exists = ( static::getDaoClass()::findOne($options) != null );
        }

        return $exists;
    }
}