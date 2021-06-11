<?php

/**
 * OrderLine
 */
class OrderLine extends BaseEntity{
    private $idOrders;
    private $idArticle;
    private $quantity;
    
    
    /**
     * getOrder
     * get the order associated with this order line
     * @return Orders
     */
    public function getOrder(): ?Orders{
        return $this->getRelatedEntity("Orders");
    }
    
    /**
     * setOrder
     * set the order associated with this order line
     * @param  mixed $o
     * @return void
     */
    public function setOrder(Orders $o){
        $this->setRelatedEntity($o);
    }
    
    /**
     * getArticle
     * get the article associated with this order line
     * @return Article
     */
    public function getArticle(): ?Article{ 
        return $this->getRelatedEntity("Article");
    }
    
    /**
     * setArticle
     * set the article associated with this order line
     * @param  mixed $a
     * @return void
     */
    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }

    
    /**
     * getSubTotal
     * get the total ammount without shipping for this order line
     * @return void
     */
    public function getSubTotal(){
        $dateCreation = $this->getOrder()->getDateCreation();
        $articlePrice = $this->getArticle()->getArticlePriceAt($dateCreation)->getPrice();

        return $articlePrice * $this->getQuantity();
    }
    
    /**
     * getFormatted
     * get human-readable version of this line
     */
    public function getFormatted(){
        return $this->getQuantity() . " " .
            $this->getArticle()->getUnit()->getName() . " : " .
            $this->getArticle()->getProduct()->getName();
    }

    /**
     * Get the value of idOrders
     */
    public function getIdOrders()
    {
        return $this->idOrders;
    }

    /**
     * Set the value of idOrders
     *
     * @return  self
     */
    public function setIdOrders($idOrders)
    {
        $this->idOrders = $idOrders;

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
}