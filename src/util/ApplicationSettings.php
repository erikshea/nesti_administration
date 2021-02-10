<?php 
class ApplicationSettings {
    private const CONFIG_FILE = "config/application.json";
    private static $settings;

    public static function get($key){
            if ( static::$settings == null ){
                $jsonString = file_get_contents(
                    SiteUtil::toAbsolute(static::CONFIG_FILE)
                );
                static::$settings = json_decode($jsonString,true);
            }

            return static::$settings[$key] ?? null;
        }
    }
?>