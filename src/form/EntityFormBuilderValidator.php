<?php


/**
 * EntityValidator
 * static methods to validate Entity properties
 */
class EntityFormBuilderValidator{
       
     /**
     * unique
     * validates if no other entity in datasource (excluding currently-checked entity) has the same property value
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function unique( $testString, $entity, String $parameterName): bool{
        $entityInDb = get_class($entity)::getDaoClass()::findOneBy($parameterName, $testString);

        // first, we must check if property value was not changed from the one in database
        if ($entityInDb != null // If entity exists with same value in the same property
            &&  $entityInDb->getId() == $entity->getId() ){ // Unique constraint is only satisfied if entity we're checking is the same as the one in database
            $valid = true;
        } else {
            $valid = $entityInDb == null;
        }

        return $valid;
    }


    
}