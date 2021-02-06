<?php
class MainController 
{
    public static function processRoute(){
        SiteUtil::sanitize($_POST); // need recursive sanitizing for multidimensional array
        SiteUtil::sanitize($_GET);

        @[$controller, $action, $id] = SiteUtil::getUrlParameters();
        if ( UserController::getLoggedInUser() == null ){
            return UserController::processAction("login");
        }

        switch ( $controller ){
            case "user":
                UserController::processAction();
            break;
            case "recipe":
            case "":
                RecipeController::processAction();
            break;
            default:
                BaseController::error();
        }

    }
}
