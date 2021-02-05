<?php 

/**
 * SiteUtil
 * Site-related convenience methods
 */
class SiteUtil{    
    /**
     * require
     * includes a file
     * @param  String $relativePath relative path of file
     * @param  bool $once require once?
     * @return void
     */
    public static function require(String $relativePath="", bool $once=true){
        $absolutePath =  self::toAbsolute("src/$relativePath");
        $once?require_once($absolutePath):require($absolutePath);
    }

     
    /**
     * toAbsolute
     * Turns a relative file path parameter into an absolute path (from project root);
     * @param  mixed $relativePath
     * @return String absolute path
     */
    public static function toAbsolute(String $relativePath): String{
        return __DIR__ . "/../../$relativePath";
    }

    
     /**
     * url
     * Transforms a relative (to project root) URL into an absolute one
     * @param  String URL $relative to project root
     * @return String absolute url
     */
    public static function url(String $relativeUrl=""): String{
        // base url is originally called script's directory
        $baseUrl = dirname($_SERVER["SCRIPT_NAME"]);

        // In local testing, we maycall a script in project root (')not /public) to simulate production .htaccess behavior
        if (self::endsWith($baseUrl,"/public")){
            $baseUrl = dirname($baseUrl);
        }

        return $baseUrl . "/" . $relativeUrl;
    }

    /**
     * getUrlParameters
     * extracts an URL of the form /my/pretty/url from $_SERVER, and returns an array of slugs
     * 
     * @return Array of slugs, ie. ['my', 'pretty', 'url']
     */
    public static function getUrlParameters(): Array{
        $parameterString =  isset($_SERVER['PATH_INFO']) ?
            // If not using an apache server (ie. VSCode PHP Server), need to use PATH_INFO and remove leading slash
            ltrim($_SERVER['PATH_INFO'],'/') :
            // Otherwise, use a QUERY_STRING passed on by an .htaccess rewrite rule
            $_SERVER['QUERY_STRING'];

        return explode('/',self::sanitize($parameterString));
    }


    public static function autoloadRegister(){
        spl_autoload_register(function ($className) {
            if ( self::endsWith($className, "Controller")){
                self::require("controller/$className.php");
            } elseif ( self::endsWith($className, "Dao")){
                self::require("model/dao/$className.php");
            } elseif ( self::endsWith($className, "Util")){
                self::require("util/$className.php");
            } else {
                self::require("model/entity/$className.php");
            }
        });
    }


        /**
     * endsWith
     * check if a string ends with another string
     * 
     * @param  mixed $haystack
     * @param  mixed $needle
     * @return void
     */
    public static function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }


     /**
     * recursively sanitize every member of an array or a non-array variable that is passed by reference. 

     * @param  mixed $dirty reference array or variable to sanitize
     * @param  int $filter filter to use with filter_var
     * @return void 
     */
    public static function sanitize(&$dirty, int $filter=FILTER_SANITIZE_STRING){
        if (!is_array($dirty)){ 
            // If dirty argument isn't an array, change it directly
            $dirty = filter_var(trim($dirty), $filter); 
        } else {
            // Walk through each member of the array, inheriting $filter for use inside anonymous function
            array_walk_recursive($dirty, function (&$value) use ($filter)  { 
                $value = filter_var(trim($value), $filter); // changes original array ($value passed by reference)
            });
        }
        return $dirty;
    }

}

SiteUtil::autoloadRegister();