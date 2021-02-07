<?php
class UsersController extends EntityController
{
    protected $entityClass = "Users";

    protected $loggedInUser;

    public function login()
    {
        $template = 'login';
        if (isset($_POST['Users'])) {

            $candidate = UsersDao::findOneBy('login', $_POST['Users']['login']);

            if ($candidate != null && $candidate->isPassword($_POST['Users']['password'])) {
                MainController::setLoggedInUser($candidate, $_POST['Users']['password']);
                header('Location: '.SiteUtil::url());
            }
        }
        $this->render($template);
    }




    public function setupTemplateVars(&$templateVars, &$templates)
    {
        parent::setupTemplateVars($templateVars, $templates);
        $templateVars['assets']['css'][] = "users";
    }

}
