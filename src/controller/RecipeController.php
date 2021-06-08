<?php

class RecipeController extends EntityController
{    
    /**
     * actionAdd
     * Edit recipe action
     * @return void
     */
    public function actionAdd(){
        $this->forward('edit');
    }
    
    /**
     * actionEdit
     * Edit recipe action
     * @return void
     */
    public function actionEdit()
    {
        $entity = $this->getEntity();

        $formBuilder = new EntityFormBuilder($entity);
        
        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);

            if ($formBuilder->isValid()) {
                if ( $_POST[$this->getEntityClass()]["imageStatus"] == "deleted" ) {
                    $entity->setIdImage(null);
                } elseif ( $_FILES["image"]["error"] == 0 ) {
                    $image = $entity->getImage() ?? new Image;

                    $image->setFromFiles("image");

                    $entity->setImage($image);
                }
                $formBuilder->applyDataTo($entity);
                $entity->setChef(MainController::getLoggedInUser()->getChef());

                $this->addVars([ "message" => $entity->existsInDataSource()?"edited":"created" ]);

                $this->getDaoClass()::saveOrUpdate($entity);
                $this->setEntity($entity);
            } else {
                $this->addVars([
                    "errors" => $formBuilder->getAllErrors(),
                    "message" => "error"
                ]);
            }
        }

        $imageUrl = null;
        if ($entity != null && $entity->getImage() != null ){
            $imageUrl = $entity->getImage()->getUrl();
        }

        $this->templateVars['assets']['js'][] = [
            'src'=>"DeleteModal.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        $this->templateVars['assets']['js'][] = [
            'src'=>"recipe-edit.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        $this->templateVars['assets']['js'][] = [
            'src'=>"ParagraphList.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        $this->addVars([
            "imageUrl" => $imageUrl,
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => $formBuilder
        ]);
    }


    public function actionImport()
    {
        $entity = $this->getEntity();

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
 

            if ( $_FILES["image"]["error"] == 0 ) {

            }
        }

        $this->addVars([
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
        ]);
    }
    
    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "recipe.css";
        $this->templateVars['assets']['css'][] = "image-upload.css";
        $this->templateVars['assets']['js'][] = [
            'src' => 'react.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'react-dom.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'babel.min.js'
        ];
    }
}
