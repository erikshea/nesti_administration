<?php

/**
 * EntityValidator
 * static methods to validate Entity properties
 */
class FormBuilderFieldTransformer{
    public static function lowercase($value){
        return strtolower($value);
    }
}