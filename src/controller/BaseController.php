<?php
SiteUtil::require('util/FormatUtil.php');



class BaseController
{
    protected $actionSlug;
    protected $templateVars = [];
    protected $templateNames = ['base'=>'common/base'];

    public function dispatch($actionSlug)
    {
        $this->actionSlug = $actionSlug;

        $actionMethod = static::translateToActionMethod($actionSlug); 
        $this->$actionMethod();

        $this->render();
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
                'route' =>   MainController::getCurrentRoute(), 
                'actionRoute' =>    MainController::getCurrentRoute()['controller'] .
                                    '/' . MainController::getCurrentRoute()['action'], 
                'actionTemplate' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
                'currentUser' => MainController::getLoggedInUser(),
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

    public function redirect($route=null){
        if ( $route == null ){
            // todo: use maincontroller routing info
            $route = $this->getActualControllerSlug();
            if ( $route == "bases" ) {
                $route = "";
            }
        }
        header('Location: '.SiteUtil::url($route));
        exit;
    }
    
    
    public function redirect404(){
        $this->redirect("error/404");
    }

    /**
     * getActualControllerSlug
     * returns the actual controller route slug called
     * a "/" homepage url may call "recipes" controller, so we can't rely on actual URL for this.
     * @return void
     */
    public function getActualControllerSlug()
    {
        // derive controller slug from controller class name
        $route = strtolower(substr(static::class, 0, -10));
        //  controller slugs are pluralized by convention
        if ( !FormatUtil::endsWith($route,'s') ){
            $route .= 's';
        }

        return $route;
    }

    public static function translateToActionMethod($actionSlug){
        return 'action' . ucfirst($actionSlug);
    }
}
