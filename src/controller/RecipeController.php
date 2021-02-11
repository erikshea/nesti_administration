<?php

class RecipeController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }


    public function actionEdit()
    {
        $imageUrl = null;
        if ( $this->getEntity() != null && $this->getEntity()->getImage() != null ){
            $imageUrl = SiteUtil::url(
                "public/assets/images/content/" . $this->getEntity()->getImage()->getFileName()
            );
        }

        $this->addVars([ "imageUrl" => $imageUrl ]);
        parent::actionEdit();
    }

    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "recipe";
        $this->templateVars['assets']['css'][] = "image-upload";
        $this->templateVars['assets']['js'][] = "recipe";
    }
}
