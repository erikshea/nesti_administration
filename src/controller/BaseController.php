<?php
SiteUtil::require('util/FormatUtil.php');



class BaseController
{
    protected static $entity;
    protected static $entityClass;
    protected static $dao;


    public static function callActionMethod($action)
    {
        method_exists(get_called_class(), $action) ?
            get_called_class()::$action() : // if action in URL exists, call it
            get_called_class()::error(); // else call default one
    }

    public static function processAction($forceAction=null)
    {
        @[,$action, $id] = SiteUtil::getUrlParameters();

        if($forceAction!=null){
            $action = $forceAction;
        }

        get_called_class()::initializeEntity($id);
        get_called_class()::callActionMethod($action);
    }

    /**
     * render
     * renders a template
     * @param  mixed $templateName template name , or null to redirect to default action
     * @return void
     */
    protected static function render($templates, $vars = [])
    {
        if ($templates == null) {
            //si le templet eest nul(ex si on delete un article => aon applele le tmplate par dafault (ici la liste))
            self::error();
        } else {
            if (!is_array($templates)) {
                 $templates =['action'=>$templates,'base'=>'common/base'];
            }
     
            get_called_class()::setupTemplateVars($vars,$templates);
        
            //repars a la racine du porjet
            include_once SiteUtil::toAbsolute('templates/'.$templates['base'].'.php');
        }
    }

    public static function setupTemplateVars(&$vars,&$templates){
            // Add shared parameters to the existing ones
            $vars = array_merge($vars, [
                'baseUrl' => SiteUtil::url(), // absolute url of public folder
                'controller' => self::class,         // current user
                'templatePath' => SiteUtil::toAbsolute("templates/" . $templates['action'] . ".php"),
                'loggedInUser' => UserController::getLoggedInUser()
            ]);
    }


    public static function error()
    {
        self::render('error/error404');
    }
}
