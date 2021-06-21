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
    
    /**
     * actionTags
     * get a list of all tags
     * @return void
     */
    public function actionTags()
    {
        $tags = EntityUtil::toArray(TagDao::findAll(), true);
        echo json_encode($tags);
    }
    
    /**
     * actionRecipesForTag
     * get a list of recipes having a given tag 
     * @return void
     */
    public function actionRecipesForTag()
    {
        $recipes = RecipeDao::findAll(["idTag" => SiteUtil::getUrlParameters()[2]]);
        echo json_encode($this->recipesToArray($recipes));
    }

        
    /**
     * actionRecipesForPartialName
     * get a list of recipes whose name contains a string
     * @return void
     */
    public function actionRecipesForPartialName()
    {
        $recipes = RecipeDao::findAll(["name LIKE" => "%" . SiteUtil::getUrlParameters()[2]. "%"] );
        echo json_encode($this->recipesToArray($recipes));
    }
    
    /**
     * actionIngredientRecipes
     * get ingredients corresponding to recipe id
     * @return void
     */
    public function actionIngredientRecipes()
    {
        $ingredientRecipes = IngredientRecipeDao::findAll(["idRecipe" => SiteUtil::getUrlParameters()[2]] );
        echo json_encode($this->ingredientRecipesToArray($ingredientRecipes));
    }

 
    
    /**
     * actionObtain
     * allows a client to obtain an API token
     * @return void
     */
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

    
    /**
     * actionParagraphs
     * get all paragraphs for a given recipe ID
     * @return void
     */
    public function actionParagraphs()
    {
        $paragraphs = (new ParagraphDao)::findAll([
            "idRecipe" => SiteUtil::getUrlParameters()[2],
            "ORDER" => "paragraphPosition ASC"
        ] );
        echo json_encode(EntityUtil::toArray($paragraphs, true));
    }
    
    /**
     * recipesToArray
     * get a list of recipes as a data array
     * @param  mixed $recipes
     */
    private function recipesToArray($recipes){
        $recipesArray =  EntityUtil::toArray($recipes, true);
        foreach ($recipesArray as $i=>&$recipeArray) {
            $recipeArray["author"] = FormatUtil::decode($recipes[$i]->getChef()?->getFullName() ?? "");
            $recipeArray["imageUrl"] = SiteUtil::fullUrl($recipes[$i]->getImage()?->getUrl() ?? "");
        }
        return $recipesArray;
    }
    
    /**
     * ingredientRecipesToArray
     * get a list of ingredients as a data array
     * @param  mixed $ingredientRecipes
     */
    private function ingredientRecipesToArray($ingredientRecipes){
        $ingredientRecipesArray =  EntityUtil::toArray($ingredientRecipes, true);
        foreach ($ingredientRecipesArray as $i=>&$ingredientRecipeArray) {
            $ingredientRecipeArray["name"] = FormatUtil::decode($ingredientRecipes[$i]->getIngredient()->getName());
            $ingredientRecipeArray["unitName"] = FormatUtil::decode($ingredientRecipes[$i]->getUnit()->getName());
        }
        return $ingredientRecipesArray;
    }
}
