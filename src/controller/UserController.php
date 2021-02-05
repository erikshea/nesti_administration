<?php

SiteUtil::require('model/entity/Users.php');
SiteUtil::require('util/FormatUtil.php');




class UserController extends BaseController
{
    protected static $entityClass = "Users";
    protected static $loggedInUser;

    public static function login()
    {
        $template = 'login';
        if (isset($_POST['Users'])) {

            $candidate = UsersDao::findOneBy('login', $_POST['Users']['login']);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password'])) {
                self::setUser($candidate, $_POST['Users']['password']);
                header('Location: '.SiteUtil::url().'recipe/list');
            }
        }
        self::render($template);
    }



    public static function getLoggedInUser(): ?Users{
        if (self::$loggedInUser == null && isset($_COOKIE['user']['login'])) {
            $candidate =  UsersDao::findOneBy('login',$_COOKIE['user']['login']);
            if ($candidate->isPassword($_COOKIE['user']['password'])){
                self::$loggedInUser = $candidate;
            }
        }
        return self::$loggedInUser;
    }

    public static function setLoggedInUser(?Users $user, $password=null){
        self::$loggedInUser = $user;

        if($user!=null){
            setcookie("user[login]", $user->getLogin(), 2147483647, '/');
            if ( $password != null ){
                setcookie("user[password]", $password, 2147483647, '/');
            }
        }
    }
}
