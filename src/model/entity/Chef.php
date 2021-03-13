<?php
class Chef extends Users{
    private $idChef;

    public function getRecipes($options=["a"]): array{
        return $this->getRelatedEntities("Recipe", $options);
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

    public function getLatestRecipe(){
        return $this->getRecipes(["ORDER"=>"dateCreation DESC","flag"=>"a"])[0] ?? null;
    }
}