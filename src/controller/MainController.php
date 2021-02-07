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
            case "user":
                (new UsersController)->processAction();
            break;
            case "recipe":
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

        if($user!=null){
            setcookie("user[login]", $user->getLogin(), 2147483647, '/');
            if ( $password != null ){
                setcookie("user[password]", $password, 2147483647, '/');
            }
        }
    }
}
