<?php

class ArticlePrice extends BaseEntity{
    protected static $columnNames; 
    
    private $idArticlePrice;
    private $dateStart;
    private $price;
    private $idArticle;
    

    public function getArticle(): ?Article{
        return $this->getRelatedEntity("Article");
    }

    public function setArticle(Article $a){
        $this->setRelatedEntity($a);
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
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of dateStart
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set the value of dateStart
     *
     * @return  self
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get the value of idArticlePrice
     */
    public function getIdArticlePrice()
    {
        return $this->idArticlePrice;
    }

    /**
     * Set the value of idArticlePrice
     *
     * @return  self
     */
    public function setIdArticlePrice($idArticlePrice)
    {
        $this->idArticlePrice = $idArticlePrice;

        return $this;
    }
}