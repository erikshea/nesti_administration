<?php

/**
 * Unit
 */
class Unit extends BaseEntity{
    private $idUnit;
    private $name;
    
    
    /**
     * getArticles
     * get all articles with this unit
     * @param  mixed $options
     * @return array
     */
    public function getArticles($options=['flag' => 'a']): array{
        return $this->getRelatedEntities("Article", $options);
    }
    
    /**
     * getIngredientRecipes
     * set all IngredientRecipes with this unit
     * @return array
     */
    public function getIngredientRecipes(): array{
        return $this->getRelatedEntities("IngredientRecipes");
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
}