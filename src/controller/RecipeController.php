<?php

class RecipeController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }

    public function actionUpdateParagraphsAjax(){
        $result = [];
        foreach ($_POST["paragraphs"] as $index=>$paragraphArray ){
            if ( ($paragraphArray['status'] ?? null) == 'toAdd' ){
                $paragraph = new Paragraph;
                $paragraph->setIdRecipe($this->getEntity()->getId());
            } else {
                $paragraph = ParagraphDao::findById($paragraphArray['idParagraph']);
            }

            if ( ($paragraphArray['status'] ?? null) == 'toDelete' ){
                ParagraphDao::delete($paragraph);
            } else {
                $paragraph->setContent($paragraphArray["content"]);
                $paragraph->setParagraphPosition($index+1);
    
                ParagraphDao::saveOrUpdate($paragraph);
                $result[] = EntityUtil::toArray($paragraph);
            }
        }

        echo json_encode($result);
    }

    public function actionGetParagraphsAjax(){
        $paragraphs = $this->getEntity()->getParagraphs(["ORDER"=>"paragraphPosition ASC"]);
        
        echo json_encode(EntityUtil::toArray($paragraphs));
    }

    public function actionGetIngredientRecipesAjax(){
        $result["ingredientRecipes"] = $this->getIngredientRecipesArray();
        $result["ingredients"] = static::getIngredientsArray();
        $result["units"] = EntityUtil::toArray(UnitDao::findAll());
        echo json_encode($result);
    }

    public function actionUpdateIngredientRecipesAjax(){
        foreach ($_POST["ingredientRecipes"] as $index=>$irArray ){
            if (    isset($irArray['status'])
                && !empty($irArray['ingredientName'])
                && !empty($irArray['unitName']) ){
                $ingredient = IngredientDao::findOneBy('name', $irArray['ingredientName']);
                if ( $ingredient == null ){
                    $ingredient = new Ingredient();
                    $ingredient->setName($irArray['ingredientName']);
                    IngredientDao::save($ingredient);
                }

                $ir = IngredientRecipeDao::findOne([
                    'idRecipe' => $this->getEntity()->getId(),
                    'idIngredient' => $ingredient->getId(),
                ]);
                
                if ( $irArray['status'] == 'toAdd' ) {
                    if ( $ir == null ){
                        $ir = new IngredientRecipe();
                        $ir->setIdRecipe($this->getEntity()->getid());
                    }
        
                    $unit = UnitDao::findOneBy('name', $irArray['unitName']);
                    if ( $unit == null ){
                        $unit = new Unit();
                        $unit->setName($irArray['unitName']);
                        UnitDao::save($unit);
                    }
                    $ir->setUnit($unit);
                    $ir->setIngredient($ingredient);
                    $ir->setQuantity($irArray['quantity']);
                    IngredientRecipeDao::saveOrUpdate($ir);
                } elseif($irArray['status'] == 'toDelete')  {
                    IngredientRecipeDao::delete($ir);
                }
            }
        }
        
        $this->actionGetIngredientRecipesAjax();
    }


    protected function getIngredientRecipesArray(){
        return array_map( function($ir) {
            return [
                'quantity' => $ir->getQuantity(),
                'unitName' => $ir->getUnit()->getName(),
                'ingredientName' => $ir->getIngredient()->getName(),
                'ingredientId' => $ir->getIngredient()->getId()
            ];
        }, $this->getEntity()->getIngredientRecipes(["ORDER"=>"recipePosition ASC"]));

    }

    protected static function getIngredientsArray(){
        return array_map( function($ingredient) {
            return [
                "name"=>$ingredient->getName()
            ];
        }, IngredientDao::findAll());
    }

    public function actionEdit()
    {
        $entity = $this->getEntity();


        $formBuilder = new EntityFormBuilder($entity);
        
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


        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);

            if ($formBuilder->isValid()) {
                if ( $_POST[$this->getEntityClass()]["imageStatus"] == "deleted" ) {
                    $entity->setIdImage(null);
                } elseif ( $_FILES["image"]["error"] == 0 ) {
                    $image = $entity->getImage();
                    if ( $image != null ){
                        unlink ( $image->getAbsolutePath() );
                    }else {
                        $image = new Image;
                    }

                    preg_match(
                        "/(.*)\.([^\.]+)$/", // capture filename + ext
                        $_FILES["image"]["name"],
                        $matches
                    ); 
                    $image->setName($matches[1]);
                    $image->setFileExtension($matches[2]);
                    $image->setDateModification(FormatUtil::currentSqlDate());
                    ImageDao::saveOrUpdate($image);
                    move_uploaded_file($_FILES["image"]["tmp_name"], $image->getAbsolutePath());
                    $entity->setImage($image);
                }
                $formBuilder->applyDataTo($entity);
                $entity->setChef(MainController::getLoggedInUser()->getChef());

                if ( $entity->existsInDataSource()){
                    $this->addVars([
                        "message" => "edited"
                    ]);
                } else {
                    $this->addVars([
                        "message" => "created"
                    ]);
                }
                $this->getDaoClass()::saveOrUpdate($entity);
                $this->setEntity($entity);
            } else {
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        }

        $imageUrl = null;
        if ($entity != null && $entity->getImage() != null ){
            $imageUrl = $entity->getImage()->getUrl() . "?dateModification=" . urlencode($entity->getImage()->getDateModification());
        }
        FormatUtil::dump($imageUrl);
        $this->addVars([
            "imageUrl" => $imageUrl,
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => $formBuilder
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
