<?php

class Ingredient extends Product{
    
    public function getIngredientRecipes(): array{
        return $this->getRelatedEntities("IngredientRecipes");
    }

}