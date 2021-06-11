<?php

/**
 * Chef
 */
class Chef extends Users{
        
    /**
     * getRecipes
     * get a chef's authored recipes
     * @param  mixed $options
     * @return array
     */
    public function getRecipes($options=["a"]): array{
        return $this->getRelatedEntities("Recipe", $options);
    }


    private $idChef;    
    
    /**
     * getIdChef
     *
     * @return void
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
    
    /**
     * getLatestRecipe
     * get most recent Recipe authored by this chef
     * @return void
     */
    public function getLatestRecipe(){
        return $this->getRecipes(["ORDER"=>"dateCreation DESC","flag"=>"a"])[0] ?? null;
    }
        
    /**
     * getUser
     * get User superclass entity
     * @return Users
     */
    public function getUser(): ?Users{
        return $this->getRelatedEntity("Users");
    }
}