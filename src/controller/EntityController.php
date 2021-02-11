<?php
class EntityController extends BaseController
{
    protected  $entity;
    protected  $entityClass;

    protected function getDaoClass(){
        return $this->getEntityClass()::getDaoClass();
    }

    public function preRender()
    {
        if ( !isset($this->templateNames['action']) ){
            $this->templateNames['action'] = $this->actionSlug;
        }
        parent::preRender();

        if (strpos($this->templateNames['action'], '/') === false) {

            $this->templateNames['action'] = strtolower($this->getEntityClass())
                . "/".$this->templateNames['action'];
        }

        // Add shared parameters to the existing ones
        $this->addVars([
            'entity' =>  $this->getEntity(),
            'actionTemplate' => SiteUtil::toAbsolute("templates/" . $this->templateNames['action'] . ".php"),
        ]);
    }


    /**
     * initializeEntity
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return mixed entity that corresponds to current controller
     */
    public function getEntity()
    {
        if ( $this->entity == null){
            @[,,$id] = SiteUtil::getUrlParameters();
            if (!empty($id)) { // If a user ID is specified in the URL

                $this->setEntity($this->getDaoClass()::findById($id)); // find corresponding user in data source
            }

            if (!$this->entity) { // If no ID specified, or wrong ID specified
                $class =  $this->getEntityClass();

                $this->setEntity(new $class);
            }
        }
        return $this->entity;
    }


    /**
     * edit
     * edit an existing recipe, or a newly-created one
     * @return void
     */
    public function actionEdit()
    {
        $this->addVars([
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => new EntityFormBuilder($this->getEntity())
        ]);

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $this->getEntity()->setParametersFromArray($_POST[$this->getEntityClass()]);
            if ($this->getEntity()->isValid()) {
                $this->getDaoClass()::saveOrUpdate($this->getEntity());
                MainController::redirect();
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
    public function actionDelete()
    {
        if (!empty($_POST)) { // if we arrived here by way of the submit button in the delete view
            $this->getDaoClass()::delete($this->getEntity());
            MainController::redirect();
        }
    }

    public function actionList()
    {
        $queryOptions = ['flag'=>'a'];
        $this->setTemplateName('common/baseNoCrumbs', 'base');

        if ( isset( $_POST["search"] )){
            foreach ( $_POST["search"] as $parameterName => $value ){
                $queryOptions[$parameterName . " LIKE"] = "%$value%";
            }
        }
  
        $this->addVars(['entities' => $this->getDaoClass()::findAll($queryOptions)]);
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


}
