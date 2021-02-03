<?php

class EntityUtil{
    public static function get($entity, $propertyName){
        return $entity->{'get' . ucFirst($propertyName)}();
    }

    public static function set(&$entity, $propertyName, $propertyValue){
        return $entity->{'set' . ucFirst($propertyName)}($propertyValue);
    }
}