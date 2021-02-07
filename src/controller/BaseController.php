<?php
SiteUtil::require('util/FormatUtil.php');



class BaseController
{
    protected  $entity;
    protected  $entityClass;
    protected  $dao;

    public function callActionMethod($action)
    {
        method_exists(get_called_class(), $action) ?
            $this->$action() : // if action in URL exists, call it
            $this->error(); // else call default one
    }

    public function processAction($forceAction=null)
    {
        @[,$action, $id] = SiteUtil::getUrlParameters();

        if($forceAction!=null){
            $action = $forceAction;
        }

        $this->callActionMethod($action);
    }

    /**
     * render
     * renders a template
     * @param  string $templateName template name , or null to redirect to default action
     * @return void
     */
    protected function render(?string $templateNames, array $vars = [])
    {
        if ($templateNames == null) {
            //si le templet eest nul(ex si on delete un article => aon applele le tmplate par dafault (ici la liste))
            $this->error();
        } else {
            if (!is_array($templateNames)) {
                 $templates =['action'=>$templateNames,'base'=>'common/base'];
            }
     
            $this->setupTemplateVars($vars,$templates);
        
            //repars a la racine du porjet
            include_once SiteUtil::toAbsolute("templates/{$templates['base']}.php");
        }
    }

    public function setupTemplateVars(&$templateVars,&$templates){
            // Add shared parameters to the existing ones
            $templateVars = array_merge($templateVars, [
                'baseUrl' => SiteUtil::url(), // absolute url of site root
                'assetsUrl' => SiteUtil::url('public/assets'), // absolute url of assets folder
                'controller' => self::class, // current user
                'templatePath' => SiteUtil::toAbsolute("templates/" . $templates['action'] . ".php"),
                'loggedInUser' => MainController::getLoggedInUser(),
                'assets' => [
                    "js" => [], // JavaScript files to include
                    "css" => [] // Stylesheets include
                ]
            ]);
    }


    public function error()
    {
        $this->render('error/error404');
    }
}
