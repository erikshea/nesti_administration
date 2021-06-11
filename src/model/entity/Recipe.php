<?php

/**
 * Recipe
 */
class Recipe extends BaseEntity{
    private $idRecipe;
    private $dateCreation;
    private $name;
    private $difficulty;
    private $portions;
    private $flag;
    private $preparationTime;
    private $idChef;
    private $idImage;
    private $idTag;

    
    /**
     * getAverageGrade
     * get average grade of this recipe
     * @return void
     */
    public function getAverageGrade(){
        $grade =null;

        $i = 0;
        $total = 0;
        foreach($this->getGrades() as $grade){
            $i++;
            $total += $grade->getRating();
        }
        return $i == 0? null:$total/$i;
    }
    
    /**
     * getGrades
     * get all grades for this recipe
     * @param  mixed $options
     * @return array
     */
    public function getGrades($options=[]): array{
        return $this->getRelatedEntities("Grades",$options);
    }
    
    /**
     * getComments
     * get all comments for this recipe
     * @return array
     */
    public function getComments(): array{
        return $this->getRelatedEntities("Comment");
    }
    
    /**
     * getParagraphs
     * get all paragraphs for this recipe
     * @param  mixed $options
     * @return array
     */
    public function getParagraphs($options=null): array{
        return $this->getRelatedEntities("Paragraph", $options);
    }
    
    /**
     * getIngredientRecipes
     * get all ingredientRecipes for this recipe
     * @param  mixed $options
     * @return array
     */
    public function getIngredientRecipes($options=[]): array{
        return $this->getRelatedEntities("IngredientRecipe",$options);
    }
        
    /**
     * getImage
     * get image for this recipe
     * @return Image
     */
    public function getImage(): ?Image{
        return $this->getRelatedEntity("Image");
    }
    
    /**
     * setImage
     * set image for this recipe
     * @param  mixed $i
     * @return void
     */
    public function setImage(Image $i){
        $this->setRelatedEntity($i);
    }
    
    /**
     * getChef
     * get author of this recipe
     * @return Chef
     */
    public function getChef(): ?Chef{ 
        return $this->getRelatedEntity("Chef");
    }
    
    /**
     * setChef
     * set author of this recipe
     * @param  mixed $c
     * @return void
     */
    public function setChef(Chef $c){
        return $this->setRelatedEntity($c);
    }
    
    /**
     * getIngredients
     *
     * @param  mixed $options
     * @return void
     */
    public function getIngredients($options=[]){
        return $this->getIndirectlyRelatedEntities("Ingredient", "IngredientRecipe", $options);
    }

    /**
     * Get the value of idImage
     */
    public function getIdImage()
    {
        return $this->idImage;
    }

    /**
     * Set the value of idImage
     *
     * @return  self
     */
    public function setIdImage($idImage)
    {
        $this->idImage = $idImage;

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
     * Get the value of preparationTime
     */
    public function getPreparationTime()
    {
        return $this->preparationTime;
    }

    /**
     * Set the value of preparationTime
     *
     * @return  self
     */
    public function setPreparationTime($preparationTime)
    {
        $this->preparationTime = $preparationTime;

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
     * Get the value of portions
     */
    public function getPortions()
    {
        return $this->portions;
    }

    /**
     * Set the value of portions
     *
     * @return  self
     */
    public function setPortions($portions)
    {
        $this->portions = $portions;

        return $this;
    }

    /**
     * Get the value of difficulty
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set the value of difficulty
     *
     * @return  self
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
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
    
    /**
     * getIdTag
     *
     * @return void
     */
    public function getIdTag()
    {
        return $this->idTag;
    }

    
    /**
     * setIdTag
     *
     * @param  mixed $idTag
     * @return void
     */
    public function setIdTag($idTag)
    {
        $this->idTag = $idTag;

        return $this;
    }
}