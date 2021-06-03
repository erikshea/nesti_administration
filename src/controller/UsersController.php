<?php
class UsersController extends EntityController
{
    public function actionLogin()
    {
        $this->setTemplateName('common/baseBarebones', 'base');
        header("HTTP/1.1 401 Unauthorized");
        $this->addVars(["formBuilder" => new EntityFormBuilder($this->getEntity())]);
        if (isset($_POST['Users'])) {
            $candidate = UsersDao::findOne(['login' => $_POST['Users']['login']]);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password']))
            {
                $candidate->initializeAuthentificationToken();
                UsersDao::saveOrUpdate($candidate);
                setcookie("user_authentification_token", $candidate->getAuthentificationToken(), 2147483647, '/');
                MainController::redirect();
            } else {
                $this->addVars(['message' => 'invalid']);
            }
        }
    }

    public function actionLogout()
    {
        $user = MainController::getLoggedInUser();
        $user?->setAuthentificationToken(null);
        UsersDao::saveOrUpdate($user);

        $_COOKIE['user_authentification_token'] = null;

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
        $user = $this->getEntity();

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
                $formBuilder->applyDataTo($user);

                $password = $formBuilder->getFormData()["password"];
                $user->setPasswordHashFromPlaintext($password);

                $this->getDaoClass()::saveOrUpdate($user);
                $formBuilder->applyDataElementTo($user,"roles");

                MainController::redirect("user/edit/".$this->getEntity()->getId());
            } else {
                $this->addVars(['message' => 'invalid']);
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        }
    }


    /**
     * edit
     * edit an existing user, or a newly-created one
     * @return void
     */
    public function actionEdit()
    {
        $formBuilder = new EntityFormBuilder($this->getEntity());
        $user = $this->getEntity();
        
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
                $formBuilder->applyDataTo($user);

                if ( !empty($formBuilder->getFormData()["password"]) ){
                    $user->setPasswordHashFromPlaintext($formBuilder->getFormData()["password"]);
                }

                $this->getDaoClass()::saveOrUpdate($user);
                $formBuilder->applyDataElementTo($user,"roles");

                $this->addVars(['message' => 'edited']);
            } else {
                $this->addVars(['message' => 'invalid']);
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        }

           
        
        $this->templateVars['assets']['js'][] = [
            'src' => 'ModerateComment.js'
        ];
        
        $this->templateVars['assets']['js'][] = [
            'src' => 'OrderItems.js',
            "type" => "text/babel"
        ];
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
