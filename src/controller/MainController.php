<?php
class MainController 
{
    protected const DEFAULT_CONTROLLER_SLUG = "recipe";

    protected static ?Users $loggedInUser = null;
    protected static $routeConfig;
    protected static $currentRoute; 

    public function dispatch(){
        SiteUtil::sanitize($_POST); // need recursive sanitizing for multidimensional array
        SiteUtil::sanitize($_GET);

        @[$controllerSlug, $actionSlug, $idSlug] = SiteUtil::getUrlParameters();
        $routeConfig = static::getRouteParameters();


        if ( empty( $controllerSlug ) ){
            $controllerSlug = static::DEFAULT_CONTROLLER_SLUG;
        }

        if ( !isset($routeConfig[$controllerSlug]) ) {
            static::redirect404();
        }

        if ( empty( $actionSlug ) ){
            $actionSlug = $routeConfig[$controllerSlug]['defaultAction'];
        }

        $controllerClass = $routeConfig[$controllerSlug]['controller'];
        $actionMethod = $controllerClass::translateToActionMethod($actionSlug);

        if ( !method_exists($controllerClass, $actionMethod) ) {
            static::redirect404();
        }

        if ( static::getLoggedInUser() == null ){
            static::redirect("user/login");
        }

        static::$currentRoute = ['controller' => $controllerSlug, 'action' => $actionSlug];

        $controllerClass = $routeConfig[$controllerSlug]['controller'];
        (new $controllerClass)->dispatch($actionSlug);
    }

    public static function getLoggedInUser(): ?Users{
        if (static::$loggedInUser == null && isset($_COOKIE['user']['login'])) {
            $candidate =  UsersDao::findOneBy('login',$_COOKIE['user']['login']);
            if ($candidate != null && $candidate->isPassword($_COOKIE['user']['password'])){
                static::$loggedInUser = $candidate;
            }
        }
        return static::$loggedInUser;
    }

    public static function setLoggedInUser( $user, $password=null){
        static::$loggedInUser = $user;


        setcookie("user[login]", $user?$user->getLogin():null, 2147483647, '/');
        setcookie("user[password]", $password, 2147483647, '/');
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

    public static function redirect(string $completeRoute = ""){
        header('Location: '.SiteUtil::url($completeRoute));
        exit;
    }

    public static function redirect404(){
        static::redirect("error/404");
    }

    public static function getCurrentRoute(){
        return static::$currentRoute;
    }

    public static function getActionRoute(){
        return static::$currentRoute['controller'] . '/' . static::$currentRoute['action'];
    }

}
