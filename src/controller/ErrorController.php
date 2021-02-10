<?php
class ErrorController extends BaseController
{
    public function action404()
    {
        header("HTTP/1.0 404 Not Found");
        $this->setTemplateName('error/404');
    }

    public function action401()
    {
        header("HTTP/1.1 401 Unauthorized");
        $this->setTemplateName('error/401');
    }


    public function preRender(){
        parent::preRender();
    }

    public function dispatch($actionSlug,$options= [])
    {
        $this->setTemplateName('common/baseNoCrumbs', 'base');
        parent::dispatch($actionSlug);
    }
}
