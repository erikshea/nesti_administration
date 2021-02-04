<?php

class Grades extends BaseEntity{
    private $idUser;
    private $idRecipe;
    private $rating;


    public function getRecipe(): ?Recipe{ 
        return $this->getRelatedEntity("Recipe");
    }

    public function setRecipe(Recipe $r){
        $this->setRelatedEntity($r);
    }

    public function getUser(): ?User{ 
        return $this->getRelatedEntity("User");
    }

    public function setUser(User $u){
        $this->setRelatedEntity($u);
    }

    
    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @return  self
     */ 
    public function setRating($rating)
    {
        $this->rating = $rating;

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
     * Get the value of idUser
     */ 
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}