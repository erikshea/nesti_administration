<?php

/**
 * FormatUtil
 * Format-related convenience methods.
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

    public static function frenchTime($date){
        if ($date ==null){
            $formattedDate = "-";
        } else {
            setlocale(LC_TIME, "fr_FR.utf8", "French");
            $formattedDate = utf8_encode(strftime("%d %B %G, %Hh%M", strtotime($date)));
        }

        return $formattedDate;
    }

    public static function currentSqlDate(){
        $dt = new DateTime();
        return $dt->format('Y-m-d H:i:s');
    }

    public static function translateFlag($flag){
        switch ($flag) {
            case 'a' : 
                $translated = "Actif";
                break;
            case 'w' :
                $translated = "En attente";
                break;
            default :
                $translated = "Bloqué";
        }

        return $translated;
    }


    public static function translateRoles( $roles ){
        $translatedRoles = [];
        foreach( $roles as $role) {
            $translatedRoles[] = static::translate($role);
        }
        return $translatedRoles;
    }

    public static function translate( $english ){

        switch ($english) {
            case 'administrator' : 
                $french = "Admin";
                break;
            case 'moderator' :
                $french = "Modérateur";
                break;
            case 'chef' :
                $french = "Chef";
        }
        return $french;
    }
}

?>