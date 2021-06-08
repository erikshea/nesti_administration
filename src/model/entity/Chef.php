<?php
class Chef extends Users{
    public function getRecipes($options=["a"]): array{
        return $this->getRelatedEntities("Recipe", $options);
    }


    private $idChef;
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
    
    public function getUser(): ?Users{
        return $this->getRelatedEntity("Users");
    }
}