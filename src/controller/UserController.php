<?php
SiteUtil::require("util/FormatUtil.php");
SiteUtil::require("entity/User.php");
/**
 * UserController
 * User-related action dispatcher
 */
class UserController{
    private $user;
    private $twig;
    /**
     * __construct
     * conestructor sanitizes request, initializes a user, and calls an action 
     * @return void
     */
    public function __construct(){
        $loader = new \Twig\Loader\FilesystemLoader(SiteUtil::toAbsolute('templates/user'));
        $this->twig = new \Twig\Environment($loader);
        
        FormatUtil::sanitize($_POST); // need recursive sanitizing for multidimensional array

        // action is first slug in url, id second
        @[$action, $id] = SiteUtil::getUrlParameters();

        $this->initializeUser($id);
        
        method_exists($this, $action)?
            $this->$action(): // if action in URL exists, call it
            $this->default(); // else call default one
    }
    
    /**
     * initializeUser
     * Sets user class parameter to a user from data source if specified in url, otherwise a new user
     * @return void
     */
    private function initializeUser($id){
        if (!empty($id)) { // If a user ID is specified in the URL
            $this->user = UserDao::findById($id); // find corresponding user in data source
        }
        
        if (!$this->user){ // If no ID specified, or wrong ID specified
            $this->user = new User;
        }
    }
    
    /**
     * edit
     * edit an existing user, or a newly-created one
     * @return void
     */
    private function edit(){
        $templateName = 'edit'; 
        $templateVars = ["isSubmitted" => !empty($_POST['user'])];

        if ($templateVars["isSubmitted"]) { // if we arrived here by way of the submit button in the edit view
            $this->user->setParametersFromArray($_POST['user']);
            if ($this->user->isValid()) {
                UserDao::saveOrUpdate($this->user);
                $templateName = null; // null template will redirect to default action
            } else {
                $templateVars["errors"] = $this->user->getErrors();
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
    private function delete(){
        $templateName = 'delete'; 

        if (!empty($_POST)) { // if we arrived here by way of the submit button in the delete view
            UserDao::delete($this->user);
            $templateName = null; 
        }

        $this->render($templateName);
    }
    
    /**
     * render
     * renders a template
     * @param  mixed $templateName template name , or null to redirect to default action
     * @return void
     */
    private function render($templateName, $templateVars=[]){
        if ($templateName == null){
            $this->default();
        }else{
            // Add shared parameters to the existing ones
            $templateVars = array_merge($templateVars, [
                'baseUrl' => SiteUtil::url(), // absolute url of project base
                'user' => $this->user         // current user
            ]);
    
            echo $this->twig->render("$templateName.twig", $templateVars); // render twig template
        }
    }

            
    /**
     * read
     * view user parameters
     * @return void
     */
    private function read(){
        $this->render('read');
    }
    
    /**
     * default
     * default action (called if no action specified, wrong action specified, or null template specified)
     * @return void
     */
    private function default(){
        $this->render("list", [
            'users' => UserDao::findAll()
        ]);
    }


}
?>