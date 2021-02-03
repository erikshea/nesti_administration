<?php

class IngredientRecipe extends BaseEntity{
    private $idProduct;
    private $idRecipe;
    private $quantity;
    private $recipeOrder;
    private $idUnit;
    


    public function getIngredient(): Ingredient{
        return $this->getRelatedEntity("Ingredient");
    }

    public function getRecipe(): Recipe{
        return $this->getRelatedEntity("Recipe");
    }

    /**
     * Get the value of idUnit
     */ 
    public function getIdUnit()
    {
        return $this->idUnit;
    }

    /**
     * Set the value of idUnit
     *
     * @return  self
     */ 
    public function setIdUnit($idUnit)
    {
        $this->idUnit = $idUnit;

        return $this;
    }

    /**
     * Get the value of recipeOrder
     */ 
    public function getRecipeOrder()
    {
        return $this->recipeOrder;
    }

    /**
     * Set the value of recipeOrder
     *
     * @return  self
     */ 
    public function setRecipeOrder($recipeOrder)
    {
        $this->recipeOrder = $recipeOrder;

        return $this;
    }

    /**
     * Get the value of quantity
     */ 
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @return  self
     */ 
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of idRecipe
     */ 
    public function getIdRecipe()
    {
        return $this->idRecipe;
    }

    /**
     * Set the value of idRecipe
     *
     * @return  self
     */ 
    public function setIdRecipe($idRecipe)
    {
        $this->idRecipe = $idRecipe;

        return $this;
    }

    /**
     * Get the value of idProduct
     */ 
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */ 
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }
}