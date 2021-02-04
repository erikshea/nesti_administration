<?php

class Comment extends BaseEntity{
    private $idComment;
    private $commentTitle;
    private $commentContent;
    private $dateCreation;
    private $flag;
    private $idRecipe;
    private $idUser;
    private $idModerator;

    
    public function getModerator(): ?Moderator{
        return $this->getRelatedEntity("Moderator");
    }
    public function setModerator(Moderator $m){
        $this->setRelatedEntity($m);
    }

    public function getUser(): ?User{
        return $this->getRelatedEntity("User");
    }

    public function setUser(User $user){
        $this->setRelatedEntity($user);
    }

    public function getRecipe(): ?Recipe{
        return $this->getRelatedEntity("Recipe");
    }

    public function setRecipe(Recipe $recipe){
        $this->setRelatedEntity($recipe);
    }
    
    public function getImage(): ?Image{
        return $this->getRelatedEntity("Image");
    }
    
    public function setImage(Image $i){
        $this->setRelatedEntity($i);
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

    /**
     * Get the value of idComment
     */
    public function getIdComment()
    {
        return $this->idComment;
    }

    /**
     * Set the value of idComment
     *
     * @return  self
     */
    public function setIdComment($idComment)
    {
        $this->idComment = $idComment;

        return $this;
    }

    /**
     * Get the value of commentTitle
     */
    public function getCommentTitle()
    {
        return $this->commentTitle;
    }

    /**
     * Set the value of commentTitle
     *
     * @return  self
     */
    public function setCommentTitle($commentTitle)
    {
        $this->commentTitle = $commentTitle;

        return $this;
    }

    /**
     * Get the value of commentContent
     */
    public function getCommentContent()
    {
        return $this->commentContent;
    }

    /**
     * Set the value of commentContent
     *
     * @return  self
     */
    public function setCommentContent($commentContent)
    {
        $this->commentContent = $commentContent;

        return $this;
    }

    /**
     * Get the value of dateCreation
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get the value of flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set the value of flag
     *
     * @return  self
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

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
     * Get the value of idUser1
     */ 
    public function getIdModerator()
    {
        return $this->idModerator;
    }

    /**
     * Set the value of idUser1
     *
     * @return  self
     */ 
    public function setIdModerator($idModerator)
    {
        $this->idModerator = $idModerator;

        return $this;
    }
}