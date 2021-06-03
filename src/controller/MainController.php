<?php
class MainController 
{
    protected static ?Users $loggedInUser = null;
    protected static $routeConfig;
    protected static $currentRoute = []; 
    protected static $currentController;

    public function dispatch(){
        SiteUtil::sanitize($_POST); // need recursive sanitizing for multidimensional array
        SiteUtil::sanitize($_GET);

        static::$currentRoute['controller'] = SiteUtil::getUrlParameters()[0] ?? "";
        static::$currentRoute['action'] = SiteUtil::getUrlParameters()[1] ?? "";

        $routeConfig = static::getRouteParameters();

        if ( static::getLoggedInUser() == null && !static::loggedInUserHasRightsForController()){
            static::forwardLogin();
        } else {
            if ( empty( static::$currentRoute['controller'] ) ){
                static::$currentRoute['controller'] = static::getDefaultControllerSlug();
            }
    
            if ( !isset($routeConfig[static::$currentRoute['controller']]) ) {
                static::redirect404();
            }
            
            if ( !static::loggedInUserHasRightsForController() ){
                static::forward401();
            }
    
            if ( empty( static::$currentRoute['action'] ) ){
                static::$currentRoute['action'] = $routeConfig[static::$currentRoute['controller']]['defaultAction'];
            }
    
            $controllerClass = $routeConfig[static::$currentRoute['controller']]['controller'];
            $actionMethod = $controllerClass::translateToActionMethod(static::$currentRoute['action']);
    
            if ( !method_exists($controllerClass, $actionMethod) ) {
                static::redirect404();
            }
    
            static::callControllerDispatch();
        }
    }

    public static function getLoggedInUser(): ?Users{
        $sessionToken = $_COOKIE['user_authentification_token'] ?? null;

        if ($sessionToken != null) {
            // null si token invalide
            static::$loggedInUser = UsersDao::findOne(['authentificationToken' => $sessionToken ]);
        }

        return static::$loggedInUser;
    }

    public static function getRouteParameters(){
        if ( static::$routeConfig == null ){
            $jsonString = file_get_contents(
                SiteUtil::toAbsolute("config/routeParameters.json")
            );
            static::$routeConfig = json_decode($jsonString,true);
        }
        return static::$routeConfig;
    }

    public static function getCurrentRoute(){
        return static::$currentRoute;
    }

    public static function getActionRoute(){
        return static::$currentRoute['controller'] . '/' . static::$currentRoute['action'];
    }

    public static function getDefaultControllerSlug(){

        $defaultSlug = 'error';
        
        foreach ( static::getRouteParameters() as $slug => $parameters){
            if ( 
                count(
                    array_intersect(static::getLoggedInUser()->getRoles(),
                    $parameters['isDefault'] ?? [] )
                ) > 0 ) {
                $defaultSlug = $slug;
            }
        }

        return $defaultSlug;
    }

    public static function getActionParameters(){
        return static::getRouteParameters()[static::$currentRoute["controller"]]["actions"][static::$currentRoute["action"]] ?? null;
    }

    public static function getCurrentController(){
        return static::$currentController;
    }


    
    public static function redirect($route=""){
        header('Location: '.SiteUtil::url($route));
        exit;
    }

    public static function redirect404(){
        static::redirect("error/404");
    }
    public static function forward401(){
        static::$currentRoute = ['controller' => 'error', 'action' => '401'];
        static::callControllerDispatch();
    }

    public static function forwardLogin(){
        static::$currentRoute = ['controller' => 'user', 'action' => 'login'];
        static::callControllerDispatch();
    }

    public static function callControllerDispatch($options=[]){
        $controllerClass = static::getRouteParameters()[static::$currentRoute['controller']]['controller'];
        
        static::$currentController = new $controllerClass;

        if ( !static::loggedInUserHasRights() ){
            static::forward401();
        } else {
            static::$currentController->dispatch(static::$currentRoute['action'],$options);
        }
    }

    
    public static function loggedInUserHasRightsForController(){
        return static::loggedInUserHasRights(true);
    }

    public static function loggedInUserHasRights($controllerOnly=false ){
        $user = static::getLoggedInUser();
        $routeParameters  = static::getRouteParameters();

        $currentAction  = $controllerOnly ? null : static::getCurrentRoute()['action'];
        $currentController  = static::getCurrentRoute()['controller'];
        $allowedForRoute = $routeParameters[$currentController]['actions'][$currentAction]['allowed']
                        ?? $routeParameters[$currentController]['allowed']
                        ?? [];

        if ( in_array("guest", $allowedForRoute) )
        {
            $isAllowed = true;
        } else if ($user==null) {
            $isAllowed = static::loggedInUserHasRightsForRoutingFunctions($allowedForRoute);
        } else {
            $currentAction  = $controllerOnly ? null : static::getCurrentRoute()['action'];
            $currentController  = static::getCurrentRoute()['controller'];

            $routeParameters  = static::getRouteParameters();

            $allowedForRoute =  $allowedForRoute
                            ??  ['all'];
            
            $isAllowed = false;    

            if (    in_array("all", $allowedForRoute)
                ||  count(array_intersect($user->getRoles(),$allowedForRoute)) > 0
                ||  static::loggedInUserHasRightsForRoutingFunctions($allowedForRoute, $user)) {
                $isAllowed = true;
            }
        }
        return $isAllowed;
    }

    private static function loggedInUserHasRightsForRoutingFunctions($allowedForRoute, $user=null){

        foreach ( $allowedForRoute as $allowedItem ){
            if( preg_match(
                "/^%(.*)%$/", // if function in the form %FUNCTION%
                $allowedItem, 
                $matches
            )) {
                $isAllowed = false;
                switch ( $matches[1] ){
                    case "hasApiToken": 
                        $apiElement = ApiElementDao::findOne(["token" => $_GET["token"] ?? null]);
                        $isAllowed = $apiElement != null;
                    break;
                    case "hasCsrfToken": 
                        $isAllowed = SecurityUtil::getCsrfToken() == ($_POST["csrf_token"] ?? null);
                    break;
                    case "recipeCreator": 
                        if ( $user != null ){
                            $recipe = static::getCurrentController()?->getEntity();
                            $isAllowed = $user->equals( $recipe->getChef() );
                        }
                    break;
                }
                if ($isAllowed){
                    return true;
                }
            }
        }
        return false;
    }
}
