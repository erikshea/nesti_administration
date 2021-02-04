<?php
class Chef extends User{
    private $idChef;

    public function getRecipes(): array{
        return $this->getRelatedEntities("Recipe");
    }

    /**
     * Get the value of idChef
     */ 
    public function getIdChef()
    {
        return $this->idChef;
    }

    /**
     * Set the value of idChef
     *
     * @return  self
     */ 
    public function setIdChef($idChef)
    {
        $this->idChef = $idChef;

        return $this;
    }
}