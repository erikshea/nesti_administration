<?php
SiteUtil::require('util/FormatUtil.php');

class BaseController
{
    protected $actionSlug;
    protected $templateVars = [ 'assets' => ["js" => [], "css" => []]];
    protected $templateNames = ['base'=>'common/base'];
    protected $hasView = true;
    protected $viewHeader = 'Content-Type: text/html; charset=utf-8';

    public function dispatch($actionSlug, $options= [])
    {
        if ( isset($options['templateVars']) ){
            $this->addVars( $options['templateVars'] );
        }

        $this->forward($actionSlug);
    }

    protected function forward($actionSlug){
        $this->actionSlug = $actionSlug;

        $actionMethod = static::translateToActionMethod($actionSlug); 
        $this->$actionMethod();

        if  ( $this->hasView ){
            $this->render();
        }
    }

    /**
     * render
     * renders a template
     * @return void
     */
    protected function render()
    {
        if ( $this->templateNames != null ){ // skip render phase if no template
            $this->preRender();
    
            $this->translateAssetOptions();

            $vars = $this->templateVars; // templates only use $vars
            header($this->viewHeader);
            include_once SiteUtil::toAbsolute("templates/{$this->templateNames['base']}.php");
        }
    }

    public function preRender(){
        // Add shared parameters to the existing ones
        $this->addVars([
            'title' => MainController::getActionParameters()["title"] ?? null,
            'version' => ApplicationSettings::get("version"),
            'baseUrl' => SiteUtil::url(), // absolute url of site root
            'assetsUrl' => SiteUtil::url('public/assets'), // absolute url of assets folder
            'route' =>   MainController::getCurrentRoute(), 
            'breadcrumbs' => $this->getBreadcrumbs(),
            'actionTemplate' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
            'currentUser' => MainController::getLoggedInUser(),
            'javascriptVariables' => array_merge(
                [
                    'baseUrl'=>SiteUtil::url(),
                    'csrf_token'=>SecurityUtil::getCsrfToken()
                ],
                $this->templateVars['javascriptVariables'] ?? [] )
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

    public static function translateToActionMethod($actionSlug){
        return empty($actionSlug)?"":'action' . ucfirst($actionSlug);
    }


    public static function getBreadcrumbs(){
        $routeParameters = MainController::getAllRouteParameters();
        $controllerSlug = MainController::getCurrentRoute()['controller'];
        $actionSlug = MainController::getCurrentRoute()['action'];

        $breadcrumbs = [$routeParameters[$controllerSlug]["name"]];

        if (isset ($routeParameters[$controllerSlug]["actions"][$actionSlug]["name"])){
            $breadcrumbs[] = $routeParameters[$controllerSlug]["actions"][$actionSlug]["name"];
        }

        return $breadcrumbs;
    }

    protected function translateAssetOptions(){
        $lastAdded = ["css"=>[], "js"=>[]];

        foreach ( $this->templateVars['assets'] as $assetType=>&$assetList ){
            foreach ( $assetList as $i=>&$asset){
                $urlKey = $assetType == "css"?"href":"src";

                if ( !is_array($asset) ) {
                    $asset = [ $urlKey=>$asset ];
                }
                if ( strpos($asset[$urlKey],'/') === false ){
                    $asset[$urlKey] = SiteUtil::url("public/assets/$assetType/{$asset[$urlKey]}");
                }

                $asset[$urlKey] .= "?version=".ApplicationSettings::get("version");

                if ( $asset["addLast"] ?? false ){
                    unset($asset["addLast"]);
                    $lastAdded[$assetType][] =  $asset;
                    unset($assetList[$i]);

                }
            }
        }
        $this->templateVars['assets'] = (array_merge_recursive($this->templateVars['assets'], $lastAdded));
    }

}
