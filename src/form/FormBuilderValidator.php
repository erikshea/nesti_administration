<?php


/**
 * EntityValidator
 * static methods to validate Entity properties
 */
class FormBuilderValidator{
        
    /**
     * notEmpty
     * validates if property value is not empty
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function notEmpty(?string $testSting): bool{
        return !empty($testSting);
    }

     /**
     * email
     * validates if property value is a valid email
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function email(?string $testSting): bool{
        return filter_var(
            $testSting,
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
    public static function telephone(?string $testSting): bool{
        return preg_match(
            "/^\+?[0-9]+$/", // only numbers, with optional "+" in front
            $testSting
        ); 
    }

     /**
     * url
     * validates if property value is a valid url 
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function url(?string $testSting): bool{
        return filter_var(
            $testSting,
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
    public static function letters(?string $testSting): bool{
        return preg_match(
            "/^[a-zA-ZÀ-ÿ\- ]*$/", // only letters, spaces, and hyphens (including accents)
            $testSting
        ); 
    }


    
     /**
     * url
     * validates if property value is a strong password.
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function strong(?string $testSting): bool{
        return self::calculatePasswordStrength($testSting) > 50;
    }

     /**
     * url
     * validates if property value contains at least one letter.
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function oneLetter(?string $testSting): bool{
        return preg_match(
            "/^.*[a-zA-ZÀ-ÿ].*$/", // at least one letter  (including accented)
            $testSting
        ); 
    }
   
     /**
     * url
     * validates if property value contains at least one number.
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function oneNumber(?string $testSting): bool{
        return preg_match(
            "/^.*[\d].*$/", // at least one number
            $testSting
        ); 
    }

     /**
     * url
     * validates if property value contains at least one number.
     * @param  mixed $entity whose property we must check
     * @param  String $parameterName name of property whose value we must check
     * @return bool true if validates
     */
    public static function betweenZeroAndFive($value): bool{
        if ( is_string($value) ){
            $value = (float) $value;
        }

        return 0 < $value && $value < 5;
    }

    private static function calculatePasswordStrength($password){
        $possibleChars = 0; // set of potentially different characters in password

    	foreach ( ["09", "az", "AZ", " /"] as $range) { 
            // If any character is within those ranges
    		if (preg_match("^.*[{$range[0]}-{$range[1]}].*$", $password)) { 
    			$possibleChars += ord($range[1]) - ord($range[0]) + 1; // add distance between the chars
    	    }
        }
    	
        // Equation source: https://www.ssi.gouv.fr/administration/precautions-elementaires/calculer-la-force-dun-mot-de-passe/
        return strlen($password) *  log($possibleChars)/log(2);
    }
}