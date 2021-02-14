<?php

class EntityUtil
{
    public static function get($entity, $propertyName)
    {
        $method =  'get' . ucFirst($propertyName);
        if(!method_exists($entity, $method) ) {
            throw new InvalidArgumentException("Undefined method \"$method\" in class ". get_class($entity));
        }

        return $entity->$method();
    }

    public static function set(&$entity, $propertyName, $propertyValue)
    {
        $method =  'set' . ucFirst($propertyName);
        if(!method_exists($entity, $method) ) {
            throw new InvalidArgumentException("Undefined method \"$method\" in class ". get_class($entity));
        }
        return $entity->$method($propertyValue);
    }

    public static function setFromArray($entity, $properties)
    {
        foreach ($properties as $propertyName => $propertyValue) {
            static::set($entity, $propertyName, $propertyValue);
        }
    }

    public static function toArray($entity)
    {
        $result = [];
        if (is_array($entity)) {
            foreach ($entity as $e) {
                $result[] = static::toArray($e);
            }
        } else {
            $propertyNames = get_class($entity)::getDaoClass()::getColumnNames();
            foreach ($propertyNames as $propertyName) {
                $result[$propertyName] = static::get($entity, $propertyName);
            }
        }
        return $result;
    }
}
