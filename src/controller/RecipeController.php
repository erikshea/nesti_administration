<?php

class RecipeController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }

    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "recipe";
        $this->templateVars['assets']['css'][] = "image-upload";
        $this->templateVars['assets']['js'][] = "recipe";
        $this->templateVars['assets']['js'][] = "image-upload";
    }
}
