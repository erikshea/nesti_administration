<?php

class OrderLine extends BaseEntity{
    private $idOrders;
    private $idArticle;
    private $quantity;
    

    public function getOrder(): ?Orders{
        return $this->getRelatedEntity("Orders");
    }

    public function setOrder(Orders $o){
        $this->setRelatedEntity($o);
    }

    public function getArticle(): ?Article{ 
        return $this->getRelatedEntity("Article");
    }

    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
    }


    public function getSubTotal(){
        return $this->getArticle()->getArticlePriceAt($this->getOrder()->getDateCreation())->getPrice()
         * $this->getQuantity();
    }

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