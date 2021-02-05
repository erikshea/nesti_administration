<?php

class Article extends BaseEntity{
    private $idArticle;
    private $unitQuantity;
    private $flag;
    private $dateCreation;
    private $dateModification;
    private $idImage;
    private $idUnit;
    private $idProduct;


    public function getArticlePrices(): array{
        return $this->getRelatedEntities("ArticlePrice");
    }

    public function getLots(): array{
        return $this->getRelatedEntities("Lot");
    }

    public function getOrderLines(): array{
        return $this->getRelatedEntities("OrderLine");
    }
    
    public function getProduct(): ?Product{
        return $this->getRelatedEntity("Product");
    }
    
    public function getUnit(): ?Unit{
        return $this->getRelatedEntity("Unit");
    }
    

    public function setUnit(Unit $u){
        $this->setRelatedEntity($u);
    }


    public function setProduct(Product $p){
        $this->setRelatedEntity($p);
    }

    public function getImage(): ?Image{
        return $this->getRelatedEntity("Image");
    }

    public function setImage(Image $i){
        $this->setRelatedEntity($i);
    }

    public function getOrders(): array{
        return $this->getIndirectlyRelatedEntities("Orders", "OrderLine", BaseDao::FLAGS['active']); 
    }

    /**
     * Get the value of idProduct
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get the value of idUnit
     */
    public function getIdUnit()
    {
        return $this->idUnit;
    }

    /**
     * Set the value of idUnit
     *
     * @return  self
     */
    public function setIdUnit($idUnit)
    {
        $this->idUnit = $idUnit;

        return $this;
    }

    /**
     * Get the value of idImage
     */
    public function getIdImage()
    {
        return $this->idImage;
    }

    /**
     * Set the value of idImage
     *
     * @return  self
     */
    public function setIdImage($idImage)
    {
        $this->idImage = $idImage;

        return $this;
    }


    /**
     * Get the value of dateCreation
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get the value of flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set the value of flag
     *
     * @return  self
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get the value of unitQuantity
     */
    public function getUnitQuantity()
    {
        return $this->unitQuantity;
    }

    /**
     * Set the value of unitQuantity
     *
     * @return  self
     */
    public function setUnitQuantity($unitQuantity)
    {
        $this->unitQuantity = $unitQuantity;

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
     * Get the value of dateModification
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set the value of dateModification
     *
     * @return  self
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }
}