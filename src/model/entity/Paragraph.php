<?php

class Paragraph extends BaseEntity{
    private $idParagraph;
    private $content;
    private $paragraphOrder;
    private $dateCreation;
    private $idRecipe;

    public function getRecipe(): Recipe{
        return $this->getRelatedEntity("Recipe");
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
    public function getParagraphOrder()
    {
        return $this->paragraphOrder;
    }

    /**
     * Set the value of paragraphOrder
     *
     * @return  self
     */ 
    public function setParagraphOrder($paragraphOrder)
    {
        $this->paragraphOrder = $paragraphOrder;

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