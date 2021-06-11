<?php

/**
 * Ingredient
 */
class Ingredient extends Product{
    private $idIngredient;

        
    /**
     * getIngredientRecipes
     * get IngredientRecipes associated with this Ingredient
     * @return array
     */
    public function getIngredientRecipes(): array{
        return $this->getRelatedEntities("IngredientRecipe");
    }


    /**
     * Get the value of idIngredient
     */ 
    public function getIdIngredient()
    {
        return $this->idIngredient;
    }

    /**
     * Set the value of idIngredient
     *
     * @return  self
     */ 
    public function setIdIngredient($idIngredient)
    {
        $this->idIngredient = $idIngredient;

        return $this;
    }
}