<?php

class EntityUtil
{
    public static function get($entity, $propertyName)
    {
        $method =  'get' . ucFirst($propertyName);

        return method_exists($entity, $method) ? $entity->$method() : false;
    }

    public static function set(&$entity, $propertyName, $propertyValue)
    {
        $method =  'set' . ucFirst($propertyName);

        return method_exists($entity, $method) ? $entity->$method($propertyValue) : false;
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
