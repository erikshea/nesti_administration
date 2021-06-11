<?php

/**
 * AjaxController
 * contains all actions callable in an Ajax request
 */
class AjaxController extends BaseController
{
    protected $hasView = false;

    
    public function dispatch($actionSlug, $options= [])
    {
        header('Content-Type: application/json');
        parent::dispatch($actionSlug, $options);
    }
    
    
    /**
     * actionModerateComment
     * set a comment's status to blocked, or active
     * @return void
     */
    public function actionModerateComment(){
        $comment = CommentDao::findOne(["idRecipe" => $_POST["idRecipe"], "idUsers" => $_POST["idUsers"]]);
        $comment->setFlag( $_POST["blocks"] == "true" ? 'b':'a' );
        $comment->setModerator(Dispatcher::getLoggedInUser()?->getModerator());
        CommentDao::saveOrUpdate($comment);
        echo json_encode(['flag' => $comment->getFlag()]);
    }
    
    
    /**
     * actionGetOrderItems
     * get order lines for a given order ID
     * @return void
     */
    public function actionGetOrderItems(){
        $order = OrdersDao::findById($_POST["idOrders"]);

        $orderItems = array_map( function($ol) {
            return [
                'idArticle' => $ol->getArticle()->getId(),
                'quantity' => $ol->getQuantity(),
                'unitName' => $ol->getArticle()->getUnit()->getName(),
                'articleName' => $ol->getArticle()->getDisplayName()
            ];
        }, $order->getOrderLines());

        echo json_encode(['id'=>$_POST["idOrders"], 'orderItems' => $orderItems]);
    }

    
    /**
     * actionUpdateParagraphs
     * update every paragraph of a recipe from a passed array
     * @return void
     */
    public function actionUpdateParagraphs(){
        $result = [];
        foreach ($_POST["paragraphs"] as $index=>$paragraphArray ){
            if ( ($paragraphArray['status'] ?? null) == 'toAdd' ){
                $paragraph = new Paragraph;
                $paragraph->setIdRecipe($_POST["idRecipe"]);
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
    
    /**
     * actionGetParagraphs
     * get a recipe's paragraphs, in order
     * @return void
     */
    public function actionGetParagraphs(){
        $recipe = RecipeDao::findById($_POST["idRecipe"]);
        $paragraphs = $recipe->getParagraphs(["ORDER"=>"paragraphPosition ASC"]);
        
        echo json_encode(EntityUtil::toArray($paragraphs));
    }
    
    /**
     * actionGetIngredientRecipes
     * get all ingredients contained in a recipe
     * @return void
     */
    public function actionGetIngredientRecipes(){
        $result["ingredientRecipes"] = $this->getIngredientRecipesArray();
        $result["ingredients"] = static::getIngredientsArray();
        $result["units"] = EntityUtil::toArray(UnitDao::findAll());
        echo json_encode($result);
    }

    /**
     * actionUpdateParagraphs
     * update the ingredients of a recipe from a passed array
     * @return void
     */
    public function actionUpdateIngredientRecipes(){
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
                    'idRecipe' => $_POST["idRecipe"],
                    'idIngredient' => $ingredient->getId(),
                ]);
                
                if ( $irArray['status'] == 'toAdd' && is_numeric($irArray['quantity']) && floatval($irArray['quantity']) >=0 ) {
                    if ( $ir == null ){
                        $ir = new IngredientRecipe();
                        $ir->setIdRecipe($_POST["idRecipe"]);
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
        
        $this->actionGetIngredientRecipes();
    }
    
    /**
     * getIngredientRecipesArray
     * get ingredients of recipe as an array of data, not entities
     * @return void
     */
    protected function getIngredientRecipesArray(){
        $recipe = RecipeDao::findById($_POST["idRecipe"]);
        return array_map( function($ir) {
            return [
                'quantity' => $ir->getQuantity(),
                'unitName' => $ir->getUnit()->getName(),
                'ingredientName' => $ir->getIngredient()->getName(),
                'ingredientId' => $ir->getIngredient()->getId()
            ];
        }, $recipe->getIngredientRecipes(["ORDER"=>"recipePosition ASC"]));

    }

        
    /**
     * getIngredientsArray
     * get all existing ingredients as a data array
     * @return void
     */
    protected static function getIngredientsArray(){
        return array_map( function($ingredient) {
            return [
                "name"=>$ingredient->getName()
            ];
        }, IngredientDao::findAll());
    }


}
