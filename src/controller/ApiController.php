<?php
class ApiController extends BaseController
{
    public function dispatch($actionSlug, $options= [])
    {
        if ($actionSlug != "obtain"){
            header('Content-Type: application/json');
            $this->hasView = false;
        }
        parent::dispatch($actionSlug, $options);
    }

    public function actionTags()
    {
        $tags = EntityUtil::toArray(TagDao::findAll());
        echo json_encode($tags);
    }

    public function actionRecipesForTag()
    {
        $recipes = RecipeDao::findAll(["idTag" => SiteUtil::getUrlParameters()[2]]);
        echo json_encode($this->recipesToArray($recipes));
    }
    public function actionRecipesForPartialName()
    {
        $recipes = RecipeDao::findAll(["name LIKE" => "%" . SiteUtil::getUrlParameters()[2]. "%"] );
        echo json_encode($this->recipesToArray($recipes));
    }

    public function actionIngredientRecipes()
    {
        $ingredientRecipes = IngredientRecipeDao::findAll(["idRecipe" => SiteUtil::getUrlParameters()[2]] );
        echo json_encode($this->ingredientRecipesToArray($ingredientRecipes));
    }

 

    public function actionObtain()
    {
        $this->setTemplateName('common/baseBarebones', 'base');
        $this->setTemplateName('api/obtain', 'action');

        $apiElement = new ApiElement;
        $formBuilder = new EntityFormBuilder($apiElement);

        if ( !empty($_POST["ApiElement"]) ) { // if we arrived here by way of the submit button
            $formBuilder->setFormData($_POST["ApiElement"]);

            if ($formBuilder->isValid()) {
                $formBuilder->applyDataTo($apiElement);
                $apiElement->initializeToken();
                ApiElementDao::saveOrUpdate($apiElement);

                $this->addVars([
                    "message" => "success",
                    "token" => $apiElement->getToken()
                ]);
            } else {
                $this->addVars([
                    'message' => 'invalid',
                    "errors" => $formBuilder->getAllErrors(),
                    "formBuilder" => $formBuilder
                ]);
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        } else {
            $this->addVars([
                "formBuilder" => $formBuilder
            ]);
        }

        $this->addVars([
            "isSubmitted" => !empty($_POST["ApiElement"])
        ]);
        $this->render();
    }


    public function actionParagraphs()
    {
        $paragraphs = (new ParagraphDao)::findAll([
            "idRecipe" => SiteUtil::getUrlParameters()[2],
            "ORDER" => "paragraphPosition ASC"
        ] );
        echo json_encode(EntityUtil::toArray($paragraphs));
    }

    private function recipesToArray($recipes){
        $recipesArray =  EntityUtil::toArray($recipes);
        foreach ($recipesArray as $i=>&$recipeArray) {
            $recipeArray["author"] = $recipes[$i]->getChef()?->getFullName() ?? "";
            $recipeArray["image"] = SiteUtil::fullUrl($recipes[$i]->getImage()?->getUrl() ?? "");
        }
        return $recipesArray;
    }

    private function ingredientRecipesToArray($ingredientRecipes){
        $ingredientRecipesArray =  EntityUtil::toArray($ingredientRecipes);
        foreach ($ingredientRecipesArray as $i=>&$ingredientRecipeArray) {
            $ingredientRecipeArray["name"] = $ingredientRecipes[$i]->getIngredient()->getName();
            $ingredientRecipeArray["unitName"] = $ingredientRecipes[$i]->getUnit()->getName();
        }
        return $ingredientRecipesArray;
    }

    
}
