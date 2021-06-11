<?php

/**
 * Grades
 */
class Grades extends BaseEntity{
    private $idUsers;
    private $idRecipe;
    private $rating;
    private $dateModification;

    
    /**
     * getRecipe
     * get recipe associated with grade
     * @return Recipe
     */
    public function getRecipe(): ?Recipe{ 
        return $this->getRelatedEntity("Recipe");
    }
    
    /**
     * setRecipe
     * set recipe associated with grade
     * @param  mixed $r
     * @return void
     */
    public function setRecipe(Recipe $r){
        $this->setRelatedEntity($r);
    }
    

    /**
     * getUser
     * get user associated with grade
     * @return Users
     */
    public function getUser(): ?Users{ 
        return $this->getRelatedEntity("Users");
    }
    
    /**
     * setUser
     * set user associated with grade
     * @param  mixed $u
     * @return void
     */
    public function setUser(Users $u){
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
    public function getIdUsers()
    {
        return $this->idUsers;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUsers($idUsers)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

        /**
     * Get the value of idUser
     */ 
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }
}