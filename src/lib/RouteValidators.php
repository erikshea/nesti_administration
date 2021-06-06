<?php


/**
 * EntityValidator
 * static methods that provide custom ways to validate the current calculated route 
 */
class RouteValidators{
    public static function recipeCreator(){
        if ( MainController::getLoggedInUser() != null ){
            $recipe = (new RecipeController)->getEntity();
            return MainController::getLoggedInUser()->equals( $recipe->getChef() );
        }
    }

    public static function hasApiToken(){
        return ApiElementDao::findOne(["token" => $_GET["token"] ?? null]) != null;
    }

    public static function hasCsrfToken(){
        return SecurityUtil::getCsrfToken() == ($_POST["csrf_token"] ?? null);
    }
}