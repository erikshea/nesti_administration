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
    }
}
