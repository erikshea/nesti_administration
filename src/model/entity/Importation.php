<?php

/**
 * Importation
 */
class Importation extends BaseEntity{
    private $idAdministrator;
    private $idArticle;
    private $orderNumberSupplier;
    private $dateImportation;

        
    /**
     * getAdministrator
     * get Administrator for this entity
     * @return Administrator
     */
    public function getAdministrator(): ?Administrator{ 
        return $this->getRelatedEntity("Administrator");
    }
    
    /**
     * setAdministrator
     * set Administrator for this entity
     * @param  mixed $a
     * @return void
     */
    public function setAdministrator(Administrator $a){
        $this->setRelatedEntity($a);
    }
    
    /**
     * getArticle
     * get Article for this entity
     * @return Article
     */
    public function getArticle(): ?Article{ 
        return $this->getRelatedEntity("Article");
    }
    
    /**
     * setArticle
     * set Article for this entity
     * @param  mixed $a
     * @return void
     */
    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }
    
    /**
     * getLot
     * get Lot for this entity
     * @return Lot
     */
    public function getLot(): ?Lot{ 
        return $this->getRelatedEntity("Lot");
    }
    
    /**
     * setLot
     * set Lot for this entity
     * @param  mixed $l
     * @return void
     */
    public function setLot(Lot $l){
        $this->setRelatedEntity($l);
    }

    /**
     * Get the value of idSupplierOrder
     */
    public function getOrderNumberSupplier()
    {
        return $this->orderNumberSupplier;
    }

    /**
     * Set the value of idSupplierOrder
     *
     * @return  self
     */
    public function setOrderNumberSupplier($orderNumberSupplier)
    {
        $this->orderNumberSupplier = $orderNumberSupplier;

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