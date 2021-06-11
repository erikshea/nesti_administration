<?php

/**
 * SiteUtil
 * Site-related utility functions
 */
class SiteUtil
{
    private const SRC_DIR = 'src';

    /**
     * require
     * includes a file
     * @param  String $relativePath relative path of file
     * @param  bool $once require once?
     * @return void
     */
    public static function require(String $relativePath = "", bool $once = true)
    {
        $absolutePath =  self::toAbsolute( self::SRC_DIR . "/$relativePath" );
        $once ? require_once($absolutePath) : require($absolutePath);
    }



    /**
     * toAbsolute
     * Turns a relative file path parameter into an absolute path (from project root);
     * @param  string $relativePath
     */
    public static function toAbsolute(string $relativePath=""): string
    {
        return __DIR__ . "/../../$relativePath";
    }

    /**
     * url
     * Transforms a relative (to project root) URL into an absolute one
     * @param  string $relativeUrl URL $relative to project root
     * @return String absolute url
     */
    public static function url(string $relativeUrl = ""): String
    {
        // base url is originally called script's directory
        $baseUrl = dirname($_SERVER["SCRIPT_NAME"]);

        // In local testing, we maycall a script in project root (')not /public) to simulate production .htaccess behavior
        if (self::endsWith($baseUrl, "/public")) {
            $baseUrl = dirname($baseUrl);
        }

        return $baseUrl . "/" . $relativeUrl;
    }

    public static function fullUrl(string $absoluteUrl){
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" :
        "http");

        return empty($absoluteUrl)?
            "" :
            "$protocol://$_SERVER[HTTP_HOST]$absoluteUrl";
    }

    /**
     * getUrlParameters
     * extracts an URL of the form /my/pretty/url from $_SERVER, and returns an array of slugs
     * 
     * @return Array of slugs, ie. ['my', 'pretty', 'url']
     */
    public static function getUrlParameters(): array
    {
        return explode('/', $_GET['route']);
    }


    
    /**
     * autoloadRegister
     * set up class autoloader
     * @return void
     */
    public static function autoloadRegister()
    {
        spl_autoload_register(function ($className) {
            foreach ( self::getIncludeDirs() as $dir ){
                $path = "$dir/$className.php";
                if ( file_exists( $path) ){
                    require $path;
                }
            }
        });
    }

    private static $includeDirs;
    
    /**
     * getIncludeDirs
     * get all dirs from which we should try to find classes
     */
    public static function getIncludeDirs()
    {
        if ( self::$includeDirs == null ){
            self::$includeDirs = [];
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(self::toAbsolute(self::SRC_DIR))
            );
            foreach ($iterator as $file) {
                if ($file->isDir() && $file->getBasename() == '.'){
                    self::$includeDirs[] = $file->getPath();
                }
            }
        }
        return self::$includeDirs;
    }


    /**
     * endsWith
     * check if a string ends with another string
     * 
     * @param  mixed $haystack
     * @param  mixed $needle
     * @return bool true if $haystack ends with $needs, false otherwise
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }


    /**
     * recursively sanitize every member of an array or a non-array variable that is passed by reference. 

     * @param  mixed $dirty reference array or variable to sanitize
     * @param  int $filter filter to use with filter_var
     * @return mixed sanitized array or string 
     */
    public static function sanitize(&$dirty, int $filter = FILTER_SANITIZE_STRING)
    {
        if (!is_array($dirty)) {
            // If dirty argument isn't an array, change it directly
            $dirty = filter_var(trim($dirty), $filter);
        } else {
            // Walk through each member of the array, inheriting $filter for use inside anonymous function
            array_walk_recursive($dirty, function (&$value) use ($filter) {
                $value = filter_var(trim($value), $filter); // changes original array ($value passed by reference)
            });
        }
        return $dirty;
    }

    
    /**
     * openSession
     * open a session
     * @return void
     */
    public static function openSession(){
        session_start();
        if ( !isset($_SESSION['csrf_token']) ){
            $_SESSION['csrf_token'] = SecurityUtil::createToken();
        }
    }
}



SiteUtil::autoloadRegister();
