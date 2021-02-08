<?php
class EntityController extends BaseController
{
    protected  $entity;
    protected  $entityClass;
    protected  $dao;

    public function callActionMethod()
    {
        if ( $this->action  == "" ){
            $this->action = 'list';
        }

        $this->dao = $this->getEntityClass()::getDaoClass();
        $this->initializeEntity();

        parent::callActionMethod();
    }

    public function preRender()
    {
        if ( !isset($this->templateNames['action']) ){
            $this->templateNames['action'] = $this->action;
        }
        parent::preRender();

        if (strpos($this->templateNames['action'], '/') === false) {

            $this->templateNames['action'] = strtolower($this->getEntityClass())
                . "/".$this->templateNames['action'];
        }

        // Add shared parameters to the existing ones
        $this->addVars([
            'entity' =>  $this->getEntity(),
            'templatePath' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
        ]);
    }


    /**
     * initializeEntity
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return void
     */
    protected function initializeEntity()
    {
        @[,,$id] = SiteUtil::getUrlParameters();
        if (!empty($id)) { // If a user ID is specified in the URL

            $this->setEntity($this->dao::findById($id)); // find corresponding user in data source
        }

        if (!$this->getEntity()) { // If no ID specified, or wrong ID specified
            $class =  $this->getEntityClass();

            $this->setEntity(new $class);
        }
    }


    /**
     * edit
     * edit an existing recipe, or a newly-created one
     * @return void
     */
    public function edit()
    {
        $this->addVars(["isSubmitted" => !empty($_POST[$this->getEntityClass()])]);

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $this->getEntity()->setParametersFromArray($_POST[$this->getEntityClass()]);
            if ($this->getEntity()->isValid()) {
                $this->dao::saveOrUpdate($this->getEntity());
                $this->redirect();
            } else {
                $this->addVars(["errors" => $this->getEntity()->getErrors()]);
            }
        }
    }

    /**
     * delete
     * shows a delete confirmation form, which if submitted deletes user
     * @return void
     */
    public function delete()
    {
        if (!empty($_POST)) { // if we arrived here by way of the submit button in the delete view
            $this->dao::delete($this->getEntity());
            $this->redirect();
        }
    }

    public function list()
    {
        $this->addVars(['entities' => $this->dao::findAll()]);
    }


    /**
     * Get the value of entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the value of entity
     */
    public function setEntity($entity)
    {

        $this->entity = $entity;
    }


    
    public function getEntityClass()
    {
        return substr(static::class, 0, -10);
    }

    public function redirect(){
        $route = strtolower($this->getEntityClass());

        if ( !FormatUtil::endsWith($this->getEntityClass(),'s') ){
            $route .= 's';
        }

        header('Location: '.SiteUtil::url($route));
    }
}
