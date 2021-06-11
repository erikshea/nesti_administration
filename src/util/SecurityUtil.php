<?php 

/**
 * FormatUtil
 * Security-related utility functions
 */
class SecurityUtil {  
    /**
     * createToken
     * create a random token string
     */
    public static function createToken(){
        return bin2hex(random_bytes(32));
    }

        
    /**
     * getCsrfToken
     * get csrf token stored ins ession variable
     */
    public static function getCsrfToken(){
        return $_SESSION['csrf_token'] ?? null;
    }
    
    /**
     * getSessionToken
     * get user's session token for authentification
     */
    public static function getSessionToken(){
        return $_SESSION['session_token'] ?? null;
    }
}