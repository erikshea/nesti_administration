<?php

class IngredientRecipeDao extends BaseDao{

    public static function getPkColumnName(){
        return [ "idRecipe", "idIngredien"]; 
    }
}