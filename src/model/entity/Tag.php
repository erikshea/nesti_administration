<?php

/**
 * Tag
 */
class Tag extends BaseEntity{
    private $idTag;
    private $name;
    
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
     * Get the value of idUnit
     */ 
    public function getIdTag()
    {
        return $this->idTag;
    }

    /**
     * Set the value of idUnit
     *
     * @return  self
     */ 
    public function setIdTag($idTag)
    {
        $this->idTag = $idTag;

        return $this;
    }
}