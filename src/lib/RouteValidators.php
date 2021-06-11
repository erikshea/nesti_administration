<?php


/**
 * EntityValidator
 * static methods that provide custom ways to validate the current calculated route 
 */
class RouteValidators{    
    /**
     * recipeCreator
     * is the current logged in user the author of the requested recipe?
     */
    public static function recipeCreator(){
        if ( Dispatcher::getLoggedInUser() != null ){
            $recipe = (new RecipeController)->getEntity();
            return Dispatcher::getLoggedInUser()->equals( $recipe->getChef() );
        }
    }
    
    /**
     * hasApiToken
     * is there a valid API token in the GET parameters?
     */
    public static function hasApiToken(){
        return ApiElementDao::findOne(["token" => $_GET["token"] ?? null]) != null;
    }
    
    /**
     * hasCsrfToken
     * is there a valid CSRF token in the POST parameters?
     */
    public static function hasCsrfToken(){
        return SecurityUtil::getCsrfToken() == ($_POST["csrf_token"] ?? null);
    }
}