<?php

/**
 * IngredientRecipe
 */
class IngredientRecipe extends BaseEntity{
    private $idIngredient;
    private $idRecipe;
    private $quantity;
    private $recipePosition;
    private $idUnit;
    
        
    /**
     * getIngredient
     * get ingredient associated with this entity
     * @return Ingredient
     */
    public function getIngredient(): Ingredient{
        return $this->getRelatedEntity("Ingredient");
    }
    
    /**
     * setIngredient
     * set ingredient associated with this entity
     * @param  mixed $i
     * @return void
     */
    public function setIngredient(Ingredient $i){
        $this->setRelatedEntity($i);
    }
    
    /**
     * getRecipe
     * get recipe associated with this entity
     * @return Recipe
     */
    public function getRecipe(): Recipe{
        return $this->getRelatedEntity("Recipe");
    }

        
    /**
     * setRecipe
     * set recipe associated with this entity
     * @param  mixed $r
     * @return void
     */
    public function setRecipe(Recipe $r){
        $this->setRelatedEntity($r);
    }
    
    /**
     * getUnit
     * get unit associated with this entity
     * @return Unit
     */
    public function getUnit(): Unit{
        return $this->getRelatedEntity("Unit");
    }

        
    /**
     * setUnit
     * set unit associated with this entity
     * @param  mixed $u
     * @return void
     */
    public function setUnit(Unit $u){
        $this->setRelatedEntity($u);
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
    public function getIdIngredient()
    {
        return $this->idIngredient;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */ 
    public function setIdIngredient($idIngredient)
    {
        $this->idIngredient = $idIngredient;

        return $this;
    }

    /**
     * Get the value of recipePosition
     */ 
    public function getRecipePosition()
    {
        return $this->recipePosition;
    }

    /**
     * Set the value of recipePosition
     *
     * @return  self
     */ 
    public function setRecipePosition($recipePosition)
    {
        $this->recipePosition = $recipePosition;

        return $this;
    }

    
    /**
     * getFormatted
     * get a human-readable version of this entity
     * @return void
     */
    public function getFormatted(){
        return $this->getQuantity() . " " . $this->getUnit()->getName() . " : " . $this->getIngredient()->getName();
    }
}