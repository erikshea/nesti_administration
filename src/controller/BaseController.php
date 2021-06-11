<?php
SiteUtil::require('util/FormatUtil.php');

/**
 * BaseController
 * Base class inherited by all other controllers, containing shared logic.
 */
class BaseController
{
    protected $actionSlug;
    protected $templateVars = [ 'assets' => ["js" => [], "css" => []]];
    protected $templateNames = ['base'=>'common/base'];
    protected $hasView = true;
    protected $viewHeader = null;
    
    /**
     * dispatch
     * called by main dispatcher, informs controller of action to call and possible options
     * @param  mixed $actionSlug
     * @param  mixed $options
     * @return void
     */
    public function dispatch($actionSlug, $options= [])
    {
        if ( isset($options['templateVars']) ){
            $this->addVars( $options['templateVars'] );
        }

        $this->forward($actionSlug);
    }
    
    /**
     * forward
     * forward an action from inside another action
     * @param  mixed $actionSlug
     * @return void
     */
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
            if ($this->viewHeader != null){
                header($this->viewHeader);
            }
            include_once SiteUtil::toAbsolute("templates/{$this->templateNames['base']}.php");
        }
    }
    
    /**
     * preRender
     * called before rendering function
     * @return void
     */
    public function preRender(){
        // Add shared parameters to the existing ones
        $this->addVars([
            'title' => Dispatcher::getActionParameters()["title"] ?? null,
            'version' => ApplicationSettings::get("version"),
            'baseUrl' => SiteUtil::url(), // absolute url of site root
            'assetsUrl' => SiteUtil::url('public/assets'), // absolute url of assets folder
            'route' =>   Dispatcher::getCurrentRoute(), 
            'breadcrumbs' => $this->getBreadcrumbs(),
            'actionTemplate' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
            'currentUser' => Dispatcher::getLoggedInUser(),
            'javascriptVariables' => array_merge(
                [
                    'baseUrl'=>SiteUtil::url(),
                    'csrf_token'=>SecurityUtil::getCsrfToken()
                ],
                $this->templateVars['javascriptVariables'] ?? [] )
        ]);
    }
    
    /**
     * addVars
     * add a set of variables accessible within the view
     * @param  mixed $templateVars
     * @return void
     */
    public function addVars($templateVars){
        $this->templateVars = array_merge($this->templateVars,$templateVars);
    }
    
    /**
     * addVar
     * add a variable accessible within the view
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function addVar($key,$value){
        $this->templateVars[$key] = $value;
    }
    
    /**
     * setTemplateName
     * set name of the template
     * @param  mixed $name name of template file, without .php
     * @param  mixed $type either "action" or base
     * @return void
     */
    public function setTemplateName($name, $type='action'){
        $this->templateNames[$type] = $name;
    }  
    
    /**
     * translateToActionMethod
     * translate a routing slug (for example "edit") into a method name, for example "actionEdit()"
     * @param  mixed $actionSlug
     */
    public static function translateToActionMethod($actionSlug){
        return empty($actionSlug)?"":'action' . ucfirst($actionSlug);
    }

    
    /**
     * getBreadcrumbs
     * get an array of breadcrumbs corresponding to current route
     */
    public static function getBreadcrumbs(){
        $routeParameters = Dispatcher::getAllRouteParameters();
        $controllerSlug = Dispatcher::getCurrentRoute()['controller'];
        $actionSlug = Dispatcher::getCurrentRoute()['action'];

        $breadcrumbs = [$routeParameters[$controllerSlug]["name"]];

        if (isset ($routeParameters[$controllerSlug]["actions"][$actionSlug]["name"])){
            $breadcrumbs[] = $routeParameters[$controllerSlug]["actions"][$actionSlug]["name"];
        }

        return $breadcrumbs;
    }
    
    /**
     * translateAssetOptions
     * initializes path info for stylesheets and script files
     * @return void
     */
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
