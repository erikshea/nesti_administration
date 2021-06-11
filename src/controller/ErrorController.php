<?php

/**
 * ErrorController
 * Controller for error pages
 */
class ErrorController extends BaseController
{
    protected $templateNames = ['base'=>'common/baseBarebones'];
    
    /**
     * action404
     * Page not found.
     * @return void
     */
    public function action404()
    {
        header("HTTP/1.0 404 Not Found");
        $this->setTemplateName('error/404');
    }
    
    /**
     * action401
     * No authorization.
     * @return void
     */
    public function action401()
    {
        header("HTTP/1.1 401 Unauthorized");
        $this->setTemplateName('error/401');
    }

}
