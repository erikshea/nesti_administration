<?php

class Unit extends BaseEntity{
    private $idUnit;
    private $name;
    

    public function getArticles(): array{
        return $this->getRelatedEntities("Article", BaseDao::FLAGS['active']);
    }

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