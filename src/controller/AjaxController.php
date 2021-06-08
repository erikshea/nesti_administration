<?php
class AjaxController extends BaseController
{
    protected $hasView = false;

    public function dispatch($actionSlug, $options= [])
    {
        header('Content-Type: application/json');
        parent::dispatch($actionSlug, $options);
    }
    
    public function actionModerateComment(){
        $comment = CommentDao::findOne(["idRecipe" => $_POST["idRecipe"], "idUsers" => $_POST["idUsers"]]);
        $comment->setFlag( $_POST["blocks"] == "true" ? 'b':'a' );
        CommentDao::saveOrUpdate($comment);
        echo json_encode(['flag' => $comment->getFlag()]);
    }
    

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

    public function actionGetParagraphs(){
        $recipe = RecipeDao::findById($_POST["idRecipe"]);
        $paragraphs = $recipe->getParagraphs(["ORDER"=>"paragraphPosition ASC"]);
        
        echo json_encode(EntityUtil::toArray($paragraphs));
    }

    public function actionGetIngredientRecipes(){
        $result["ingredientRecipes"] = $this->getIngredientRecipesArray();
        $result["ingredients"] = static::getIngredientsArray();
        $result["units"] = EntityUtil::toArray(UnitDao::findAll());
        echo json_encode($result);
    }

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
                
                if ( $irArray['status'] == 'toAdd' ) {
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

    protected static function getIngredientsArray(){
        return array_map( function($ingredient) {
            return [
                "name"=>$ingredient->getName()
            ];
        }, IngredientDao::findAll());
    }


}
