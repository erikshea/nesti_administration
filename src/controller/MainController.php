<?php
class MainController 
{
    protected static ?Users $loggedInUser = null;

    public function processRoute(){
        SiteUtil::sanitize($_POST); // need recursive sanitizing for multidimensional array
        SiteUtil::sanitize($_GET);

        @[$controller, $action, $id] = SiteUtil::getUrlParameters();
        if ( static::getLoggedInUser() == null ){
            (new UsersController)->processAction("login");
            exit;
        }

        switch ( $controller ){
            case "users":
                (new UsersController)->processAction();
            break;
            case "recipes":
            case "":
                (new RecipeController)->processAction();
            break;
            default:
                (new BaseController)->error();
        }

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
}
