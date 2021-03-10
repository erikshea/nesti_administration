<?php

class Paragraph extends BaseEntity{
    private $idParagraph;
    private $content;
    private $paragraphPosition;
    private $dateCreation;
    private $idRecipe;

    public function getRecipe($options=['a']): Recipe{
        return $this->getRelatedEntity("Recipe",$options);
    }

    public function setRecipe(Recipe $r){
        $this->setRelatedEntity($r);
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
     * Get the value of paragraphOrder
     */ 
    public function getParagraphPosition()
    {
        return $this->paragraphPosition;
    }

    /**
     * Set the value of paragraphOrder
     *
     * @return  self
     */ 
    public function setParagraphPosition($paragraphPosition)
    {
        $this->paragraphPosition = $paragraphPosition;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of idParagraph
     */ 
    public function getIdParagraph()
    {
        return $this->idParagraph;
    }

    /**
     * Set the value of idParagraph
     *
     * @return  self
     */ 
    public function setIdParagraph($idParagraph)
    {
        $this->idParagraph = $idParagraph;

        return $this;
    }
}