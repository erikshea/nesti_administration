<?php
class UsersController extends EntityController
{
    public function actionLogin()
    {
        $this->setTemplateName('common/baseNoNav', 'base');

        $this->addVars(["formBuilder" => new EntityFormBuilder($this->entity)]);
        if (isset($_POST['Users'])) {

            $candidate = UsersDao::findOneBy('login', $_POST['Users']['login']);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password'])) {
                MainController::setLoggedInUser($candidate, $_POST['Users']['password']);
                $this->redirect();
            } else {
                $this->addVars(['message' => 'invalid']);
            }
        }
    }

    public function actionLogout()
    {
        $this->setTemplateName('login');
        $this->setTemplateName('common/baseNoNav', 'base');
        MainController::setLoggedInUser(null);

        $this->addVars([
            'message' => 'disconnect',
            "formBuilder" => new EntityFormBuilder($this->entity)
        ]);
    }


    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "users";
    }

}
