<?php

/**
 * City
 */
class City extends BaseEntity{
    private $idCity;
    private $name;
    private $zipCode;
    
    /**
     * getUsers
     * get users living in this city
     * @param  mixed $options
     * @return array
     */
    public function getUsers($options=['a']): array{
        return $this->getRelatedEntities("Users", $options);
    }



    /**
     * Get the value of idCity
     */ 
    public function getIdCity()
    {
        return $this->idCity;
    }

    /**
     * Set the value of idCity
     *
     * @return  self
     */ 
    public function setIdCity($idCity)
    {
        $this->idCity = $idCity;

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
     * Get the value of name
     */ 
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

}