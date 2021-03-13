<?php

class Lot extends BaseEntity{
    private $orderNumber;
    private $unitCost;
    private $dateReception;
    private $quantity;
    private $idArticle;


    public function getSubTotal(){
        return $this->getUnitCost() * $this->getQuantity();
    }

    public function getArticle(): ?Article{
        return $this->getRelatedEntity("Article");
    }

    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }


    public function getImportations($options=[]): array{
        return $this->getRelatedEntities("Importation",$options);
    }

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
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set the value of orderNumber
     *
     * @return  self
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

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