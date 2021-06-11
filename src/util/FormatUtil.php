<?php

/**
 * FormatUtil
 * Formatting-related convenience methods.
 */
class FormatUtil {    

    
    /**
     * dump
     * generates an html representation of a variable
     * 
     * @param  mixed $var to display in html
     * @return void
     */
    public static function dump($var){
        echo "<pre>".htmlentities(print_r($var, true))."</pre>";
    }

        
    /**
     * dd
     * dumps a variable, then terminates script 
     * @param  mixed $var
     * @return void
     */
    public static function dd($var){
        self::dump($var);
        die();
    }

    
    /**
     * endsWith
     * check if a string starts with another string
     * 
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    public static function startsWith( string $haystack, string $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, $length ) === $needle;
    }

        
    /**
     * endsWith
     * check if a string ends with another string
     * 
     * @param  mixed $haystack
     * @param  mixed $needle
     * @return bool true if $haystack ends with $needle, false otherwise
     */
    public static function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }


    
    /**
     * formatTime
     * format a time in minutes into human-readable form
     * @param  mixed $minutes
     * @return void
     */
    public static function formatTime($minutes){
        $hours = floor($minutes/60);
        $minutes = $minutes%60;

        $result = "";

        if ( $hours != 0 ){
            $result .= $hours . 'h';
        }

        if ( $minutes != 0 ){
            $result .= $minutes . ' min';
        }

        return $result;
    }

    
    /**
     * currentSqlDate
     * get current date, formatted for insertion into a database
     * @return void
     */
    public static function currentSqlDate(){
        return static::dateTimeToSqlDate(new DateTime());
    }
    
    /**
     * dateTimeToSqlDate
     * transforma  DateTime object into an equivalent string insertable into a database 
     * @param  mixed $dt
     * @return void
     */
    public static function dateTimeToSqlDate($dt){
        return $dt->format('Y-m-d H:i:s');
    }
    
    /**
     * sqlDateToPhpDate
     * transform an sql date into a PHP DateTime
     * @param  mixed $sqlDate
     * @return void
     */
    public static function sqlDateToPhpDate($sqlDate){
        return date('Y-m-d H:i:s', strtotime($sqlDate));
    }
    
    /**
     * getFormattedPrice
     * format a price into human readable form
     * @param  mixed $price
     * @return void
     */
    public static function getFormattedPrice($price){
        return $price == null? "-":number_format($price, 2, ",", "") . "€";
    }
    
    /**
     * decode
     * decode special characters and unicode 
     * @param  mixed $value
     * @return void
     */
    public static function decode($value){
        return html_entity_decode($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}

?>