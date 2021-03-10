<?php
class UsersController extends EntityController
{
    public function actionLogin()
    {
        $this->setTemplateName('common/baseBarebones', 'base');

        $this->addVars(["formBuilder" => new EntityFormBuilder($this->getEntity())]);
        if (isset($_POST['Users'])) {
            $candidate = UsersDao::findOneBy('login', $_POST['Users']['login']);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password'])) {
                MainController::setLoggedInUser($candidate, $_POST['Users']['password']);
                MainController::redirect();
            } else {
                $this->addVars(['message' => 'invalid']);
            }
        }
    }

    public function actionLogout()
    {
        MainController::setLoggedInUser(null);

        $this->addVars([
            'message' => 'disconnect',
        ]);

        $this->forward("login");
    }



    /**
     * Add
     * create a new user
     * @return void
     */
    public function actionAdd()
    {
        $formBuilder = new EntityFormBuilder($this->getEntity());
        $entity = $this->getEntity();

        $this->addVars([
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => $formBuilder
        ]);

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            
            if ( !isset($_POST["Users"]["roles"]) ){
                $_POST["Users"]["roles"] = [];
            }
            
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);
            if ($formBuilder->isValid()) {
                $formBuilder->applyDataTo($entity);
                $this->getDaoClass()::saveOrUpdate($entity);
                $formBuilder->applyDataElementTo($entity,"roles");

                MainController::redirect("user/edit/".$this->getEntity()->getId());
            } else {
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        }
    }


    public function actionList()
    {
        $queryOptions = [];
        $this->setTemplateName('common/baseNoCrumbs', 'base');

        if ( !empty( $_POST["search"] )){
            $like = "%" . $_POST["search"] . "%";
            $queryOptions["firstName LIKE"] =  $like;
            $queryOptions["OR lastName LIKE"] = $like;
        }
        
        $this->templateVars['assets']['js'][] = [
            'src'=>"DeleteModal.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        $this->addVars(['entities' => $this->getDaoClass()::findAll($queryOptions)]);
    }


    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "user.css";
        $this->templateVars['assets']['js'][] = [
            'src' => 'react.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'react-dom.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'babel.min.js'
        ];
    }
}
