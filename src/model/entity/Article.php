<?php

/**
 * Article
 */
class Article extends BaseEntity{
    protected static $columnNames; 
    
    private $idArticle;
    private $unitQuantity;
    private $flag;
    private $dateCreation;
    private $dateModification;
    private $idImage;
    private $idUnit;
    private $idProduct;
    private $displayName;
    private $factoryName;

        
    /**
     * getQuantitySold
     * Quantity sold of current article
     */
    public function getQuantitySold(){
        $quantity = 0;

        foreach ($this->getOrderLines() as $orderLine){
            $quantity += $orderLine->getQuantity();
        }

        return $quantity;
    }
    
    /**
     * getTotalSales
     * Sum of all sales for this article
     * @return void
     */
    public function getTotalSales(){
        $total = 0;

        foreach ($this->getOrderLines() as $orderLine){
            $total += $orderLine->getSubTotal();
        }

        return $total;
    }

    
    /**
     * getTotalPurchases
     * Sum of all purchases for this article
     * @return void
     */
    public function getTotalPurchases(){
        $total = 0;

        foreach ($this->getLots() as $lot){
            $total += $lot->getSubTotal();
        }

        return $total;
    }
    

    
    /**
     * getQuantityPurchased
     * get the total quantity purchased from suppliers
     */
    public function getQuantityPurchased(){ // TODO
        return (float) $this->getLots(["SELECT"=>"SUM(quantity)"])[0][0] ?? 0;
    }



    /**
     * getArticlePrices
     * get a list of article prices for this article
     * @param  mixed $options
     * @return array
     */
    public function getArticlePrices($options): array{
        return $this->getRelatedEntities("ArticlePrice",$options);
    }

    /**
     * getArticlePrices
     * get a list of imported lots for this article
     * @param  mixed $options
     * @return array
     */
    public function getLots($options=[]): array{
        return $this->getRelatedEntities("Lot", $options);
    }
    
    /**
     * getOrderLines
     * get order lines which contain this article
     * @return array
     */
    public function getOrderLines(): array{
        return $this->getRelatedEntities("OrderLine");
    }
        
    /**
     * getProduct
     * get the Product entity related to this article
     * @return Product
     */
    public function getProduct(): ?Product{
        return $this->getRelatedEntity("Product");
    }

    
    /**
     * setProduct
     * set the Product entity related to this article
     * @param  mixed $p
     * @return void
     */
    public function setProduct(Product $p){
        $this->setRelatedEntity($p);
    }

    /**
     * getUnit
     * get this article's unit
     * @return Unit
     */
    public function getUnit(): ?Unit{
        return $this->getRelatedEntity("Unit");
    }
    
    
    /**
     * setUnit
     * set this article's unit
     * @param  mixed $u
     * @return void
     */
    public function setUnit(Unit $u){
        $this->setRelatedEntity($u);
    }


    
    /**
     * getImage
     * get this article's image entity
     * @return Image
     */
    public function getImage(): ?Image{
        return $this->getRelatedEntity("Image");
    }

    
    /**
     * setImage
     * set this article's image entity
     * @param  mixed $i
     * @return void
     */
    public function setImage(Image $i){
        $this->setRelatedEntity($i);
    }

    
    /**
     * getArticlePriceAt
     * Get an article's price at a given date
     * @param  mixed $date
     */
    public function getArticlePriceAt($date){
        return $this->getArticlePrices(["dateStart <" => $date, "ORDER" => "dateStart DESC"])[0] ?? null;
    }
    
    /**
     * getOrders
     * get all orders which contain this article
     * @param  mixed $options
     * @return array
     */
    public function getOrders($options=[]): array{
        return $this->getIndirectlyRelatedEntities("Orders", "OrderLine", $options); 
    }
    
    /**
     * getSellingPrice
     * get the selling price of an article
     * @return void
     */
    public function getSellingPrice(){
        $highestPricedLot = $this->getLots(['ORDER' => 'unitCost DESC'])[0] ?? null;
    
        $price = null;
        if ( $highestPricedLot != null ){
            $price = $highestPricedLot->getUnitCost() * 1.2;
        }

        return $highestPricedLot == null? null:$price;
    }
    
    /**
     * getLastImportationDate
     * get the lasted importation which contains this article
     * @return void
     */
    public function getLastImportationDate(){
        $lastImportedLot = $this->getLots(['ORDER'=>'dateReception DESC'])[0] ?? null;

        $date = null;
        if ( $lastImportedLot != null ){
            $date = $lastImportedLot->getDateReception();
        }

        return $date;
    }
    
    /**
     * getStock
     * get the total available stock of an article
     * @return void
     */
    public function getStock(){
        $stock = $this->getQuantityPurchased() - $this->getQuantitySold();
        if ($stock < 0){
            $stock = 0;
        }
        return $stock;
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

    /**
     * Get the value of displayName
     */ 
    public function getDisplayName()
    {
        if ( $this->displayName == null && $this->getProduct() != null ){
          $this->displayName = $this->getProduct()->getName();
        }

        return $this->displayName;
    }

    /**
     * Set the value of displayName
     *
     * @return  self
     */ 
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

        /**
     * Get the value of displayName
     */ 
    public function getFactoryName()
    {
        return $this->factoryName;
    }

    /**
     * Set the value of displayName
     *
     * @return  self
     */ 
    public function setFactoryName($factoryName)
    {
        $this->factoryName = $factoryName;

        return $this;
    }
}