<?php
class EntityController extends BaseController
{
    public function callActionMethod($action)
    {
        if ( $action  == "" ){
            $action = 'list';
        }

        parent::callActionMethod($action);
    }

    public function processAction($forceAction=null)
    {
        @[,$action, $id] = SiteUtil::getUrlParameters();

        if($forceAction!=null){
            $action = $forceAction;
        }

        $this->dao = $this->getEntityClass()::getDaoClass();
        $this->initializeEntity($id);
        $this->callActionMethod($action);
    }

    public function setupTemplateVars(&$templateVars, &$templates)
    {
        parent::setupTemplateVars($templateVars, $templates);
        if (strpos($templates['action'], '/') === false) {

            $templates['action'] = strtolower($this->getEntityClass()) . "/".$templates['action'];
        }

        // Add shared parameters to the existing ones
        $templateVars = array_merge($templateVars, [
            'entity' =>  $this->getEntity(),
            'templatePath' => SiteUtil::toAbsolute("templates/" . $templates['action'] . ".php"),
        ]);
    }


    /**
     * initializeEntity
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return void
     */
    protected function initializeEntity($id)
    {
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
        $templateName = 'edit';
        $templateVars = ["isSubmitted" => !empty($_POST[$this->getEntityClass()])];

        if ($templateVars["isSubmitted"]) { // if we arrived here by way of the submit button in the edit view
            $this->getEntity()->setParametersFromArray($_POST[$this->getEntityClass()]);
            if ($this->getEntity()->isValid()) {
                $this->dao::saveOrUpdate($this->getEntity());
                $templateName = null; // null template will redirect to default action
            } else {
                $templateVars["errors"] = $this->getEntity()->getErrors();
            }
        }

        // template remains "edit" if no POST user parameters, or if user parameters in POST are invalid
        $this->render($templateName, $templateVars);
    }

    /**
     * delete
     * shows a delete confirmation form, which if submitted deletes user
     * @return void
     */
    public  function delete()
    {
        $templateName = 'delete';

        if (!empty($_POST)) { // if we arrived here by way of the submit button in the delete view
            $this->dao::delete($this->getEntity());
            $templateName = null;
        }

        $this->render($templateName);
    }

    public function list()
    {
        $this->render("list", [
            'entities' => $this->dao::findAll()
        ]);
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
}
