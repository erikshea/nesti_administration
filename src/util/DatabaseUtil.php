<?php

/**
 * Database
 * Provides a PDO connection object
 */
class DatabaseUtil
{
    // Default database connection parameters, depending on HTTP_HOST
    private static $defaultConnectionParameters = ["user" => "root", "db_name" => "tp_php", "password" => "", "host" => "localhost"];
    private static $connectionParameters = null;
    private static $conn = null; // Connection object
    
    /**
     * connect
     *  connect to a database, return resulting connection
     * @return PDO connection object
     */
    public static function connect(): ?PDO {
        if (self::$connectionParameters == null){ 
            // If connection parameters not initiated, pull them from a JSON file
            $jsonString = file_get_contents(__DIR__ . "/../../config/databaseParameters.json");
            self::$connectionParameters = json_decode($jsonString,true);
        }

        if (self::$conn == null) {
            $address = $_SERVER['SERVER_NAME']; // get host address, without the port

            if (isset(self::$connectionParameters[$address])) { 
                $parameters = self::$connectionParameters[$address];
            } else {
                $parameters = self::$defaultConnectionParameters; // if no parameters specified for current host, revert to defaults
            }

            try {
                self::$conn = new PDO(
                    "mysql:host={$parameters['host']};dbname={$parameters['db_name']}",
                    $parameters['user'],
                    $parameters['password']
                );
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$conn;
    }

    public static function disconnect() {
        self::$conn = null;
    }
}
