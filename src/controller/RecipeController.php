<?php

class RecipeController extends EntityController
{
    public function actionAdd(){
        $this->dispatch('edit');
    }
}
