<?php 

/**
 * FormatUtil
 * Security-related convenience methods.
 */
class SecurityUtil {  
    public static function createToken(){
        return bin2hex(random_bytes(32));
    }

    public static function getCsrfToken(){
        return $_SESSION['csrf_token'] ?? null;
    }

    public static function getSessionToken(){
        return $_SESSION['session_token'] ?? null;
    }
}