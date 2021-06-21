<?php

/**
 * TranslateUtil
 * language-related utility functions
 */
class TranslateUtil {
    const TRANSLATIONS = [
        "default"=>[
        ],
        "Users"=>[
            "administrator"=>"Administrateur",
            "moderator"=>"Modérateur",
            "chef"=>"Chef",
            "a"=>"Actif",
            "b"=>"Bloqué",
            "w"=>"En attente",
        ],
        "Orders"=>[
            "a"=>"Payée",
            "b"=>"Annulée",
            "w"=>"En attente",
        ],
        "Comment"=>[
            "a"=>"Approuvé",
            "b"=>"Bloqué",
            "w"=>"En attente",
        ]
    ];
    
    /**
     * translate
     * translate a string from TRANSLATIONS array
     * @param  mixed $key
     * @param  mixed $dataSet
     */
    public static function translate( $key, $dataSet="default" ){
        return static::TRANSLATIONS[$dataSet][$key] ?? "";
    }

    
    /**
     * translateArray
     * translate an array of stringsfrom TRANSLATIONS array
     * @param  mixed $strings
     * @param  mixed $dataSet
     */
    public static function translateArray( $strings, $dataSet="default" ){
        $translated=[];

        foreach ($strings as $string){
            $translated[] = static::translate($string,$dataSet);
        }
        
        return $translated;
    }
    
    /**
     * translateDate
     * translate a date into current region
     * @param  mixed $date
     */
    public static function translateDate($date){
        if ($date ==null){
            $formattedDate = "-";
        } else {
            setlocale(LC_TIME, "fr_FR.UTF-8", "French");
            $formattedDate = strftime("%d %B %G, %Hh%M", strtotime($date));

            if(ApplicationSettings::get("environment") == "dev"){ // avoid double encoding on local server (chrome issue with dates)
                $formattedDate = utf8_encode($formattedDate);
            }
        }

        return $formattedDate;
    }
    
    /**
     * translateNumber
     * tranlaste a number's decimal separator
     * @param  mixed $number
     */
    public static function translateNumber($number){
        return str_replace (".",",",$number);
    }
}