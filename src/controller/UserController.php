<?php

// SiteUtil::require('model/entity/Recipe.php');
SiteUtil::require('model/entity/Users.php');
SiteUtil::require('util/FormatUtil.php');
SiteUtil::require('controller/BaseController.php');




class UserController extends BaseController
{

    protected static $entityClass = "Users";

    public static function login()
    {

        $template = 'login';
        if (isset($_POST['Users'])) {

            $candidate = UsersDao::findOneBy('login', $_POST['Users']['username']);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password'])) {
                echo "login ";
                self::setUser($candidate);
                header('Location: '.SiteUtil::url().'loc=recipe');
            }
        }
        self::render($template);
    }


    /**
     * Get the value of user
     */
    public static function getUser()
    {
        
        if (isset($_SESSION['userLogin'])) {
            self::$entity = UsersDao::findOneBy('login', $_SESSION['userLogin']);
        }
        return self::$entity;
    }
    public static function setUser($user){

        self::setEntity(null);
        if($user!=null){
            self::setEntity($user);
            // session_start();
            $_SESSION['userLogin'] = $user->getLogin();
        }
    }
}
