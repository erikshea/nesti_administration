<?php

/**
 * EntityUtil
 * entity-related utility functions
 */
class EntityUtil
{    
    /**
     * get
     * call a getter by name
     * @param  mixed $entity
     * @param  mixed $propertyName
     */
    public static function get($entity, $propertyName)
    {
        $method =  'get' . ucFirst($propertyName);
        if(!method_exists($entity, $method) ) {
            throw new InvalidArgumentException("Undefined method \"$method\" in class ". get_class($entity));
        }

        return $entity->$method();
    }
    
    /**
     * set
     * set a property by name and value
     * @param  mixed $entity
     * @param  mixed $propertyName
     * @param  mixed $propertyValue
     */
    public static function set(&$entity, $propertyName, $propertyValue)
    {
        $method =  'set' . ucFirst($propertyName);
        if(!method_exists($entity, $method) ) {
            throw new InvalidArgumentException("Undefined method \"$method\" in class ". get_class($entity));
        }
        return $entity->$method($propertyValue);
    }
    
    /**
     * setFromArray
     * set properties from an associative array
     * @param  mixed $entity
     * @param  mixed $properties
     */
    public static function setFromArray($entity, $properties)
    {
        foreach ($properties as $propertyName => $propertyValue) {
            static::set($entity, $propertyName, $propertyValue);
        }
    }
    
    /**
     * toArray
     * get an associative array representing all properties of an entity that are present in data source
     * @param  mixed $entity
     * @param  mixed $decodeHtml
     */
    public static function toArray($entity, $decodeHtml = false)
    {
        $result = [];
        if (is_array($entity)) {
            foreach ($entity as $e) {
                $result[] = static::toArray($e, $decodeHtml);
            }
        } else {
            $propertyNames = get_class($entity)::getDaoClass()::getColumnNames();
            foreach ($propertyNames as $propertyName) {
                $result[$propertyName] = static::get($entity, $propertyName);
                if ( $decodeHtml ){
                    $result[$propertyName] = FormatUtil::decode($result[$propertyName]);
                }
            }
        }
        return $result;
    }
}
