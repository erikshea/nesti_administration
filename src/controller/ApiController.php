<?php
class ApiController extends BaseController
{
    protected $hasView = false;

    public function dispatch($actionSlug, $options= [])
    {
        header('Content-Type: application/json');
        parent::dispatch($actionSlug, $options);
    }

    protected function forward($actionSlug){
        $this->actionSlug = $actionSlug;

        $actionMethod = static::translateToActionMethod($actionSlug); 
        $this->$actionMethod();
    }

    public function actionTags()
    {
        $tags = EntityUtil::toArray(TagDao::findAll());
        echo json_encode($tags);
    }

    public function actionRecipesForTag()
    {
        $recipes = (new RecipeDao)::findAll(["idTag" => SiteUtil::getUrlParameters()[2]]);
        echo json_encode($this->recipesToArray($recipes));
    }
    public function actionRecipesForPartialName()
    {
        $recipes = (new RecipeDao)::findAll(["name LIKE" => "%" . SiteUtil::getUrlParameters()[2]. "%"] );
        echo json_encode($this->recipesToArray($recipes));
    }


    private function recipesToArray($recipes){
        $recipesArray =  EntityUtil::toArray($recipes);
        foreach ($recipesArray as $i=>&$recipeArray) {
            $recipeArray["image"] = SiteUtil::fullUrl($recipes[$i]->getImage()?->getUrl() ?? "");
        }
        return $recipesArray;
    }
}
