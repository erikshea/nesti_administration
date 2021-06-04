<?php

/**
 * MainController
 * main controller. calls all other controllers according to requested route and current user
 */
class MainController 
{
    protected static ?Users $loggedInUser = null; // currently connected user
    protected static $routeConfig; // all route configurations
    protected static $currentRoute = []; // current calculated route, in the form ["controller" => CONTROLLER, "action => ACTION]
    protected static $currentController; // instance to current (calculated) controller class

        
    /**
     * dispatch
     * determines the controller class and action method to call according to URL and user's rights
     * @return void
     */
    public function dispatch(){
        // recursively sanitize request parameters 
        SiteUtil::sanitize($_POST);
        SiteUtil::sanitize($_GET);

        // get slugs from url
        static::$currentRoute['controller'] = SiteUtil::getUrlParameters()[0] ?? "";
        static::$currentRoute['action'] = SiteUtil::getUrlParameters()[1] ?? "";

        // get all routing parameters from config
        $routeConfig = static::getAllRouteParameters(); 

        // if no controller specified (home page), find default controller
        if ( empty( static::$currentRoute['controller'] ) ){
            static::$currentRoute['controller'] = static::getDefaultControllerSlug();
        }

        // if no action specified, find default action for controller
        if ( empty( static::$currentRoute['action'] ) ){
            static::$currentRoute['action'] = $routeConfig[static::$currentRoute['controller']]['defaultAction'] ?? null;
        }
 
        // find controller class for route as well as action method (if they exist)
        $controllerClass = $routeConfig[static::$currentRoute['controller']]['controller'] ?? "";
        $actionMethod = BaseController::translateToActionMethod(static::$currentRoute['action']);

        if ( !method_exists($controllerClass, $actionMethod) ) { 
            // if no action corresponds to calculated route
            static::redirect404();
        } else{
            static::$currentController = new $controllerClass;
            if ( static::getLoggedInUser() == null && !static::userHasRights()){
                // if valid route, and guest doesn't have access to that route (via "guest" or routing functions in "allowed" routing parameters)
                static::forwardLogin(); 
            } else if ( !static::userHasRights() ){
                // if valid route, and logged in user doesn't have rights
                static::forward401(); 
            } else {
                // proceed to requested page
                static::callControllerDispatch();
            }
        }
    }

        
    /**
     * getLoggedInUser
     * get currenty logged in user
     * @return Users user, or null if guest
     */
    public static function getLoggedInUser(): ?Users{
        if (static::$loggedInUser == null && $_COOKIE['user_authentification_token'] != null) {
            static::$loggedInUser = UsersDao::findOne(['authentificationToken' => $_COOKIE['user_authentification_token'] ]);
        }

        return static::$loggedInUser;
    }
    
    /**
     * getRouteParameters
     * get parameters for all routes from config
     * @return array
     */
    public static function getAllRouteParameters():array{
        if ( static::$routeConfig == null ){
            $jsonString = file_get_contents(
                SiteUtil::toAbsolute("config/routeParameters.json")
            );
            static::$routeConfig = json_decode($jsonString,true);
        }
        return static::$routeConfig;
    }

        
    /**
     * getCurrentRoute
     * Current calculated controller and action (not necessarily the same as the slugs)
     * @return array
     */
    public static function getCurrentRoute(){
        return static::$currentRoute;
    }

        
    /**
     * getActionRoute
     * get full calculated route (including controller) to action, in the form CONTROLLER/ACTION
     * @return string
     */
    public static function getActionRoute(){
        return static::$currentRoute['controller'] . '/' . static::$currentRoute['action'];
    }

        
    /**
     * getDefaultControllerSlug
     * get slug for default controller for current user type (ie "recipe" for chef, "user" for moderator...)
     * @return string
     */
    public static function getDefaultControllerSlug(){
        $defaultSlug = 'user'; 
        
        foreach ( static::getAllRouteParameters() as $slug => $parameters){
            if ( 
                count(
                    array_intersect(static::getLoggedInUser()?->getRoles() ?? [],
                    $parameters['isDefault'] ?? [] )
                ) > 0 ) {
                $defaultSlug = $slug;
            }
        }

        return $defaultSlug;
    }

        
    /**
     * getActionParameters
     * get route parameters (from config) for current action, or null if none specified (in optional "actions" array)
     * @return array?
     */
    public static function getActionParameters(){
        return static::getAllRouteParameters()[static::$currentRoute["controller"]]["actions"][static::$currentRoute["action"]] ?? null;
    }

        
    /**
     * getCurrentController
     * get instance of current calculated controller class
     * @return mixed
     */
    public static function getCurrentController(){
        return static::$currentController;
    }


        
    /**
     * redirect
     * redirect to another route, and stop program execution
     * @param  mixed $route
     * @return void
     */
    public static function redirect($route=""){
        header('Location: '.SiteUtil::url($route));
        exit;
    }

        
    /**
     * redirect404
     * redirect to "page not found" page (rewriting URL)
     * @return void
     */
    public static function redirect404(){
        static::redirect("error/404");
    }

    /**
     * redirect404
     * forward to "not authorized" page
     * @return void
     */
    public static function forward401(){
        static::$currentRoute = ['controller' => 'error', 'action' => '401'];
        static::callControllerDispatch();
    }

    /**
     * redirect404
     * forward to login page
     * @return void
     */
    public static function forwardLogin(){
        static::$currentRoute = ['controller' => 'user', 'action' => 'login'];
        static::callControllerDispatch();
    }

        
    /**
     * callControllerDispatch
     * call current calculated controller's "dispatch" method, so that it can proceed with calculated action
     * @param  mixed $options
     * @return void
     */
    public static function callControllerDispatch($options=[]){
        static::$currentController->dispatch(static::$currentRoute['action'],$options);
    }

        
    /**
     * userHasRights
     * does the current user (or guest) have rights to requested route?
     * @return bool
     */
    public static function userHasRights(){
        $user = static::getLoggedInUser();
        $routeParameters  = static::getAllRouteParameters();

        $currentAction  = static::getCurrentRoute()['action'];
        $currentController  = static::getCurrentRoute()['controller'];

        // get list of allowed users/functions for route 
        $allowedForRoute = $routeParameters[$currentController]['actions'][$currentAction]['allowed'] // action parameters take priority (if $controllerOnly is false)
                        ?? $routeParameters[$currentController]['allowed']  // if none, use controller parameters
                        ?? []; // if unspecified, no allowed items

        $isAllowed =
                static::userHasRightsForRoutingFunctions($allowedForRoute, $user) // if one of the routing functions validates
            ||  in_array("guest", $allowedForRoute)                  // ...or guests allowed
            ||  $user != null && in_array("user", $allowedForRoute)  // ...or all users allowed
            ||  $user != null && count(array_intersect($user->getRoles(),$allowedForRoute)) > 0; // ...or one of the user's roles allowed

        return $isAllowed;
    }

    /**
     * userHasRights
     * do the routing functions specified in route config validate for the current user (or guest)?
     * @return bool
     */
    private static function userHasRightsForRoutingFunctions($allowedForRoute, $user=null){
        $isAllowed = false;

        foreach ( $allowedForRoute as $allowedItem ){
            if( preg_match(
                "/^%(.*)%$/", // if function in the form %FUNCTION%
                $allowedItem, 
                $matches
            )) {
                switch ( $matches[1] ){
                    case "hasApiToken": 
                        $apiElement = ApiElementDao::findOne(["token" => $_GET["token"] ?? null]);
                        $isAllowed |= $apiElement != null;
                    break;
                    case "hasCsrfToken": 
                        $isAllowed |= SecurityUtil::getCsrfToken() == ($_POST["csrf_token"] ?? null);
                    break;
                    case "recipeCreator": 
                        if ( $user != null ){
                            $recipe = static::getCurrentController()?->getEntity();
                            $isAllowed |= $user->equals( $recipe->getChef() );
                        }
                    break;
                }
            }
        }

        return $isAllowed;
    }
}
