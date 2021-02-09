<?php
class ErrorController extends BaseController
{
    
    public function action404()
    {
        $this->setTemplateName('error/404');
    }


}
