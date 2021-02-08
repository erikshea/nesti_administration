<?php

class EntityUtil{
    public static function get($entity, $propertyName){
        return $entity->{'get' . ucFirst($propertyName)}();
    }

    public static function set(&$entity, $propertyName, $propertyValue){
        return $entity->{'set' . ucFirst($propertyName)}($propertyValue);
    }

    public static function setFromArray($entity, $properties){
        foreach( $properties as $propertyName=>$propertyValue){
            static::set($entity,$propertyName,$propertyValue);
        }
    }

    public static function getArray($entity){
        $propertyNames = get_class($entity)::getDaoClass()::getColumnNames();

        $properties = [];
        foreach( $propertyNames as $propertyName){
            $properties[$propertyName] = static::get($entity, $propertyName);
        }
        return $properties;
    }
}