<?php


/**
 * EntityValidator
 * static methods to validate Entity properties
 */
class EntityValidator{
        
    /**
     * notEmpty
     * validates if property value is not empty
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function notEmpty($entity, String $parameterName): bool{
        return !empty(self::_get($entity, $parameterName));
    }

     /**
     * email
     * validates if property value is a valid email
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function email($entity, String $parameterName): bool{
        return filter_var(
            self::_get($entity, $parameterName),
            FILTER_VALIDATE_EMAIL
        );
    }

     /**
     * telephone
     * validates if property value is a valid telephone number
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function telephone($entity, String $parameterName): bool{
        return preg_match(
            "/^\+?[0-9]+$/", // only numbers, with optional "+" in front
            self::_get($entity, $parameterName)
        ); 
    }

     /**
     * url
     * validates if property value is a valid url 
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function url($entity, String $parameterName): bool{
        return filter_var(
            self::_get($entity, $parameterName),
            FILTER_VALIDATE_URL // Need to use strict identical operator with FILTER_VALIDATE_URL
        ) === true;  
    }

     /**
     * url
     * validates if property value is made up of letters, spaces, and hyphens
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function letters($entity, String $parameterName): bool{
        return preg_match(
            "/^[a-zA-ZÀ-ÿ\- ]*$/", // only letters, spaces, and hyphens (including accents)
            self::_get($entity, $parameterName)
        ); 
    }


     /**
     * unique
     * validates if no other entity in datasource (excluding currently-checked entity) has the same property value
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function unique($entity, String $parameterName): bool{
        $value = self::_get($entity, $parameterName);
        $entityInDb = $entity->getDaoClass()::findOneBy($parameterName, $value);

        // first, we must check if property value was not changed from the one in database
        if ($entityInDb != null // If entity exists with same value in the same property
            &&  $entityInDb->getId() == $entity->getId() ){ // Unique constraint is only satisfied if entity we're checking is the same as the one in database
            $valid = true;
        } else {
            $valid = $entityInDb == null;
        }

        return $valid;
    }
    
    /**
     * _get
     * convenience method: gets a property value from an object
     * @param  mixed $entity entity whose property we need the value of
     * @param  String $parameterName name of property whose value we need
     * @return mixed property value
     */
    private static function _get($entity, String $parameterName){
        return $entity->{"get".ucfirst($parameterName)}();
    }
}