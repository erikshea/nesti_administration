<?php

/**
 * EntityController
 * Base controller for all entity-related controllers
 */
class EntityController extends BaseController
{
    protected  $entity;
    protected  $entityClass;

    protected function getDaoClass(){
        return $this->getEntityClass()::getDaoClass();
    }

    
    /**
     * preRender
     * called before rendering the view
     */
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

        $this->templateVars['javascriptVariables']['entity'] = EntityUtil::toArray( $this->getEntity());
    }


    /**
     * initializeEntity
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return mixed entity that corresponds to current controller
     */
    public function getEntity(): mixed
    {
        if ( $this->entity == null){
            $id = SiteUtil::getUrlParameters()[2] ?? null;
            if (!empty($id)) { // If a user ID is specified in the URL

                $this->setEntity($this->getDaoClass()::findById($id)); // find corresponding user in data source
            }

            if ($this->entity == null) { // If no ID specified, or wrong ID specified
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
    public function actionAdd()
    {
        $this->actionEdit();
    }

    /**
     * edit
     * edit an existing recipe, or a newly-created one
     * @return void
     */
    public function actionEdit()
    {
        $formBuilder = new EntityFormBuilder($this->getEntity());

        $this->addVars([
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => $formBuilder
        ]);

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);

            if ($formBuilder->isValid()) {
                $this->getDaoClass()::saveOrUpdate($this->getEntity());
                Dispatcher::redirect();
            } else {
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
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
        $entity = $this->getEntity();
        $entity->setFlag("b");
        get_class($entity)::getDaoClass()::saveOrUpdate($entity);
        Dispatcher::redirect();
    }
    
    /**
     * actionList
     * list entities
     * @return void
     */
    public function actionList()
    {
        $queryOptions = ['flag'=>'a'];
        $this->setTemplateName('common/baseNoCrumbs', 'base');

        if ( isset( $_POST["search"] )){
            foreach ( $_POST["search"] as $parameterName => $value ){
                $queryOptions[$parameterName . " LIKE"] = "%$value%";
            }
        }
        
        $this->templateVars['assets']['js'][] = [
            'src'=>"DeleteModal.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        $this->addVars(['entities' => $this->getDaoClass()::findAll($queryOptions)]);
    }



    /**
     * Set the value of entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

        
    /**
     * getEntityClass
     * get the entity class corresponding to controller. by default, the controller name without "Controller"
     */
    public function getEntityClass()
    {
        return substr(static::class, 0, -10);
    }


}
