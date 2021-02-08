<?php
SiteUtil::require('util/FormatUtil.php');



class BaseController
{
    protected $action;
    protected $templateVars = [];
    protected $templateNames = ['base'=>'common/base'];

    
    public function callActionMethod()
    {
        $action = $this->action;
        method_exists(get_called_class(), $this->action) ?
            $this->$action() : // if action in URL exists, call it
            $this->error(); // else call default one

        $this->render();
    }

    public function processAction($forceAction=null)
    {
        $this->action = @SiteUtil::getUrlParameters()[1];

        if($forceAction!=null){
            $this->action = $forceAction;
        }

        $this->callActionMethod();
    }

    /**
     * render
     * renders a template
     * @param  string $templateName template name , or null to redirect to default action
     * @return void
     */
    protected function render()
    {
        $this->preRender();
    
        $vars = $this->templateVars;
        include_once SiteUtil::toAbsolute("templates/{$this->templateNames['base']}.php");
    }

    public function preRender(){
            // Add shared parameters to the existing ones
            $this->addVars([
                'version' => random_int(0,8000000000000000), // absolute url of site root
                'baseUrl' => SiteUtil::url(), // absolute url of site root
                'assetsUrl' => SiteUtil::url('public/assets'), // absolute url of assets folder
                'controller' => self::class, // current user
                'templatePath' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
                'loggedInUser' => MainController::getLoggedInUser(),
                'assets' => [
                    "js" => [], // JavaScript files to include
                    "css" => [] // Stylesheets include
                ]
            ]);
    }

    public function addVars($templateVars){
        $this->templateVars = array_merge($this->templateVars,$templateVars);
    }

    public function addVar($key,$value){
        $this->templateVars[$key] = $value;
    }

    public function setTemplateName($name, $type='action'){
        $this->templateNames[$type] = $name;
    }   

    public function error()
    {
        $this->setTemplateName('error/error404');
    }

    public function redirect(){
        header('Location: '.SiteUtil::url());
    }
}
