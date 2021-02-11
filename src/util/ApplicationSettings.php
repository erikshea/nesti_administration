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

                // random version number in dev, to auto-refresh all assets
                if ( static::$settings["environment"] == "dev"){
                    static::$settings["version"] = random_int(0,8000000000000000);
                }
            }

            return static::$settings[$key] ?? null;
        }
    }
?>