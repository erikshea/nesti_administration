<?php

class Importation extends BaseEntity{
    private $idAdministrator;
    private $idArticle;
    private $idSupplierOrder;
    private $dateImportation;

    
    public function getAdministrator(): ?Administrator{ 
        return $this->getRelatedEntity("Administrator");
    }

    public function setAdministrator(Administrator $a){
        $this->setRelatedEntity($a);
    }

    public function getArticle(): ?Article{ 
        return $this->getRelatedEntity("Article");
    }

    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }

    public function getLot(): ?Lot{ 
        return $this->getRelatedEntity("Lot");
    }

    public function setLot(Lot $l){
        $this->setRelatedEntity($l);
    }

    /**
     * Get the value of idSupplierOrder
     */
    public function getIdSupplierOrder()
    {
        return $this->idSupplierOrder;
    }

    /**
     * Set the value of idSupplierOrder
     *
     * @return  self
     */
    public function setIdSupplierOrder($idSupplierOrder)
    {
        $this->idSupplierOrder = $idSupplierOrder;

        return $this;
    }

    /**
     * Get the value of dateImportation
     */
    public function getDateImportation()
    {
        return $this->dateImportation;
    }

    /**
     * Set the value of dateImportation
     *
     * @return  self
     */
    public function setDateImportation($dateImportation)
    {
        $this->dateImportation = $dateImportation;

        return $this;
    }

    /**
     * Get the value of idAdministrator
     */ 
    public function getIdAdministrator()
    {
        return $this->idAdministrator;
    }

    /**
     * Set the value of idAdministrator
     *
     * @return  self
     */ 
    public function setIdAdministrator($idAdministrator)
    {
        $this->idAdministrator = $idAdministrator;

        return $this;
    }

    /**
     * Get the value of idArticle
     */ 
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * Set the value of idArticle
     *
     * @return  self
     */ 
    public function setIdArticle($idArticle)
    {
        $this->idArticle = $idArticle;

        return $this;
    }
}