<?php

/**
 * ApiElement
 */
class ApiElement extends BaseEntity{
    private $idApiElement;
    private $name;
    private $dateExpiration;
    private $token;

    /**
     * Get the value of idApiElement
     */ 
    public function getIdApiElement()
    {
        return $this->idApiElement;
    }

    /**
     * Set the value of idApiElement
     *
     * @return  self
     */ 
    public function setIdApiElement($idApiElement)
    {
        $this->idApiElement = $idApiElement;

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
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }


    public function initializeToken(){
        $this->setToken(bin2hex(random_bytes(16)));
    }

    /**
     * Get the value of dateExpiration
     */ 
    public function getDateExpiration()
    {
        return $this->dateExpiration;
    }

    /**
     * Set the value of dateExpiration
     *
     * @return  self
     */ 
    public function setDateExpiration($dateExpiration)
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }
}