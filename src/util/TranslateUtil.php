<?php

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

    public static function translate( $key, $dataSet="default" ){
        return static::TRANSLATIONS[$dataSet][$key] ?? "";
    }


    public static function translateArray( $strings, $dataSet="default" ){
        $translated=[];

        foreach ($strings as $string){
            $translated[] = static::translate($string,$dataSet);
        }
        
        return $translated;
    }

    public static function translateDate($date){
        if ($date ==null){
            $formattedDate = "-";
        } else {

            /*            setlocale(LC_TIME, "fr_FR.UTF-8", "French");
            $formattedDate = strftime("%d %B %G, %Hh%M", strtotime($date));
            $formattedDate = mb_convert_encoding($formattedDate, 'UTF-8', "ASCII");*/
            setlocale(LC_TIME, "fr_FR.utf8", "French");
            $formattedDate = utf8_encode(strftime("%d %B %G, %Hh%M", strtotime($date)));
        }

        return $formattedDate;
    }

    public static function translateNumber($number){
        return str_replace (".",",",$number);
    }
}