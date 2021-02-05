<?php
SiteUtil::require('util/FormatUtil.php');



class BaseController
{

    protected static $entity;
    protected static $entityClass;
    protected static $dao;




    public static function callActionMethod($action)
    {
        if ( $action  == "" ){
            $action = 'list';
        }

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
        self::$dao = self::getEntityClass()::getDaoClass();
        get_called_class()::callActionMethod($action);
    }


    /**
     * render
     * renders a template
     * @param  mixed $templateName template name , or null to redirect to default action
     * @return void
     */
    protected static function render($templateName, $vars = [])
    {
        if ($templateName == null) {
            //si le templet eest nul(ex si on delete un article => aon applele le tmplate par dafault (ici la liste))
            self::error();
        } else {
            if (strpos($templateName, '/') === false) {

                $templateName = strtolower(self::getEntityClass()) . "/$templateName";
            }
            // Add shared parameters to the existing ones
            $vars = array_merge($vars, [
                'baseUrl' => SiteUtil::url() , // absolute url of public folder
                'entity' =>  self::getEntity(),         // current user
                'controller' => self::class,         // current user
                'templatePath' => SiteUtil::toAbsolute("templates/$templateName.php")
            ]);
            //pour que ca fonctionne pour toutes les aciton, on passe le nom du template
            include  SiteUtil::toAbsolute("templates/common/base.php");
            //    echo $this->twig->render("$templateName.twig", $vars); // render twig template
        }
    }

    /**
     * initializeEntity
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return void
     */
    protected static function initializeEntity($id)
    {
        if (!empty($id)) { // If a user ID is specified in the URL

            self::setEntity(self::$dao::findById($id)); // find corresponding user in data source
        }

        if (!self::getEntity()) { // If no ID specified, or wrong ID specified

            $class =  self::getEntityClass();

            
            self::setEntity(new $class);
        }
    }


    /**
     * edit
     * edit an existing recipe, or a newly-created one
     * @return void
     */
    public static function edit()
    {
        $templateName = 'edit';
        $templateVars = ["isSubmitted" => !empty($_POST[self::getEntityClass()])];

        if ($templateVars["isSubmitted"]) { // if we arrived here by way of the submit button in the edit view
            self::getEntity()->setParametersFromArray($_POST[self::getEntityClass()]);
            if (self::getEntity()->isValid()) {
                self::$dao::saveOrUpdate(self::getEntity());
                $templateName = null; // null template will redirect to default action
            } else {
                $templateVars["errors"] = self::getEntity()->getErrors();
            }
        }

        // template remains "edit" if no POST user parameters, or if user parameters in POST are invalid
        self::render($templateName, $templateVars);
    }

    /**
     * delete
     * shows a delete confirmation form, which if submitted deletes user
     * @return void
     */
    public static function delete()
    {
        $templateName = 'delete';

        if (!empty($_POST)) { // if we arrived here by way of the submit button in the delete view
            self::$dao::delete(self::getEntity());
            $templateName = null;
        }

        self::render($templateName);
    }

    public static function list()
    {
        self::render("list", [
            'entities' => self::$dao::findAll()
        ]);
    }

    public static function error()
    {
        self::render('error/error404');
    }

    /**
     * Get the value of entity
     */
    public static function getEntity()
    {
        return get_called_class()::$entity;
    }

    /**
     * Get the value of entity
     */
    public static function setEntity($entity)
    {

        get_called_class()::$entity = $entity;
    }


    
    public static function getEntityClass()
    {

        return get_called_class()::$entityClass;
    }
}