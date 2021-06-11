<?php

/**
 * Lot
 */
class Lot extends BaseEntity{
    private $orderNumberSupplier;
    private $unitCost;
    private $dateReception;
    private $quantity;
    private $idArticle;

    
    /**
     * getSubTotal
     * get this lot's subtotal without shipping
     * @return void
     */
    public function getSubTotal(){
        return $this->getUnitCost() * $this->getQuantity();
    }
    
    /**
     * getArticle
     * get article associated with this lot
     * @return Article
     */
    public function getArticle(): ?Article{
        return $this->getRelatedEntity("Article");
    }
    
    /**
     * setArticle
     * set article associated with this lot
     * @param  mixed $a
     * @return void
     */
    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }

    
    /**
     * getImportations
     * get importations associated with this lot
     * @param  mixed $options
     * @return array
     */
    public function getImportations($options=[]): array{
        return $this->getRelatedEntities("Importation",$options);
    }
    
    /**
     * getAdmins
     * get administrators associated with this lot
     * @param  mixed $options
     * @return array
     */
    public function getAdmins($options=['a']): array{
        return $this->getIndirectlyRelatedEntities("Users", "Importation", $options); 
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

    /**
     * Get the value of orderNumber
     */
    public function getOrderNumberSupplier()
    {
        return $this->orderNumberSupplier;
    }

    /**
     * Set the value of orderNumber
     *
     * @return  self
     */
    public function setOrderNumberSupplier($orderNumberSupplier)
    {
        $this->orderNumberSupplier = $orderNumberSupplier;

        return $this;
    }

    /**
     * Get the value of dateCreation
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateReception($dateReception)
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @return  self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of unitCost
     */
    public function getUnitCost()
    {
        return $this->unitCost;
    }

    /**
     * Set the value of unitCost
     *
     * @return  self
     */
    public function setUnitCost($unitCost)
    {
        $this->unitCost = $unitCost;

        return $this;
    }
}