<?php

class ArticleController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }


    public function actionEdit()
    {
        $entity = $this->getEntity();


        $formBuilder = new EntityFormBuilder($entity);
        $formBuilder->addFormData([
            "name" => $entity->getProduct()->getName(),
            'sellingPrice' => FormatUtil::getFormattedPrice($entity->getSellingPrice()),
            'stock' => $entity->getStock()
        ]);

        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);

            if ($formBuilder->isValid()) {
                if ( $_POST[$this->getEntityClass()]["imageStatus"] == "deleted" ) {
                    $entity->setIdImage(null);
                } elseif ( $_FILES["image"]["error"] == 0 ) {
                    $image = $entity->getImage() ?? new Image;
                    
                    $image->setFromFiles("image");

                    $entity->setImage($image);
                }
                $formBuilder->applyDataTo($entity);
                $entity->setDateModification(new DateTime);

                $this->addVars([
                    "message" => "edited"
                ]);
                $this->getDaoClass()::saveOrUpdate($entity);
                $this->setEntity($entity);
            } else {
                $this->addVars(["errors" => $formBuilder->getAllErrors()]);
            }
        }

        $imageUrl = null;
        if ($entity != null && $entity->getImage() != null ){
            $imageUrl = $entity->getImage()->getUrl();
        }
        $this->addVars([
            "imageUrl" => $imageUrl,
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
            "formBuilder" => $formBuilder
        ]);
    }

    public function actionOrders()
    {
        $queryOptions = [];
        $this->setTemplateName('common/baseNoCrumbs', 'base');

        if ( !empty( $_POST["search"] )){
            $like = "%" . $_POST["search"] . "%";
            $queryOptions["firstName LIKE"] =  $like;
            $queryOptions["OR lastName LIKE"] = $like;
        }

        $userIds = array_map(function($product){
            return $product->getId();
        }, UsersDao::findAll($queryOptions));


        if (!empty ($userIds)){
            $orders = OrdersDao::findAll(["idUsers IN " => "(" . implode(",", $userIds) . ")"]);
        } else {
            $orders=[];
        }
        $this->addVars(['orders' => $orders]);

        
        $this->templateVars['assets']['js'][] = [
            'src' => 'OrderItems.js',
            "type" => "text/babel"
        ];
    }


    public function actionList()
    {
        $queryOptions = [];
        $this->setTemplateName('common/baseNoCrumbs', 'base');

        if ( isset( $_POST["search"] )){
            foreach ( $_POST["search"] as $parameterName => $value ){
                $queryOptions[$parameterName . " LIKE"] = "%$value%";
            }
        }
        
        $productIds = array_map(function($product){
            return $product->getId();
        }, ProductDao::findAll($queryOptions));
        if (!empty ($productIds)){
            $articles = ArticleDao::findAll(["idProduct IN " => "(" . implode(",", $productIds) . ")"]);
        } else {
            $articles=[];
        }

        $this->templateVars['assets']['js'][] = [
            'src'=>"DeleteModal.js",
            "type" => "text/babel",
            "addLast" => true
        ];
        $this->addVars(['entities' => $articles]);
    }

    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "article.css";
        $this->templateVars['assets']['css'][] = "image-upload.css";
        $this->templateVars['assets']['js'][] = [
            'src' => 'react.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'react-dom.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'babel.min.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'article.js',
            "type" => "text/babel"
        ];
    }

    /**
     * delete
     * shows a delete confirmation form, which if submitted deletes article
     * @return void
     */
    public function actionDelete()
    {
        $article = $this->getEntity();
        $article->setFlag('b');
        ArticleDao::saveOrUpdate($article);
        $this->addVars(['message' => "deleted"]);
        $this->forward("list");
    }


    public function actionImport(){
        $formBuilder = new FormBuilder();

        if ( !empty( $_FILES["import_file"]["tmp_name"])){
            $file = fopen($_FILES["import_file"]["tmp_name"], "r");

            // the first line contains view column names, ie: "article_idArticle";"article_name";"article_quantity";...
            $columnNames = fgetcsv($file, 0, ";");

            $importedArticles = [];
            while ( ($row = fgetcsv($file, 0, ";")) !== false ){
                $values = [];
                foreach ( $columnNames as $i=>$name ){
                    $values[$name] = utf8_encode($row[(string)$i]);
                }

                try {
                    $importedArticle = $this->importCsvLine($values);
                    if ( $importedArticle != null ){
                        $importedArticles[] = $importedArticle;
                    }
                } catch (Exception $e) {
                    $this->addVars(["message" => "error"]);
                }
            }

            $this->addVars([
                "importedArticles" => $importedArticles
            ]);
        }


        $this->addVars([
            "formBuilder" => $formBuilder,
            "isSubmitted" => !empty($_POST[$this->getEntityClass()]),
        ]);
    }



    private function importCsvLine($values){
        $importedArticle = null;

        $unit = UnitDao::findOne(["name" => $values["unit_name"]]);
        if ($unit == null) {
            $unit = new Unit;
            $unit->setName($values["unit_name"]);
            UnitDao::save($unit);
        }
        
        $product = ProductDao::findOne(["name" => $values["article_name"]]);
        if ($product == null && $values["article_name"] != "null") {
            $product = new Product;
            $product->setName($values["article_name"]);
            ProductDao::save($product);
            if ($values["ingredient_idProduct"] != "null") {
                $product->makeIngredient();
            }
        }

        $article = ArticleDao::findById($values["article_idArticle"]);
        if ($article == null) {
            $article = new Article;
            $article->setId($values["article_idArticle"]);
            $article->setProduct($product);
            $article->setUnit($unit);
            $article->setUnitQuantity($values["article_quantity"]);
            $article->setDisplayName($values["article_name"]);
            $article->setFlag($values["article_flag"]);
            ArticleDao::save($article);
            $importedArticle = $article;
        }


        $arrticlePriceDate = FormatUtil::sqlDateToPhpDate($values["offers_startDate"]);

        $articlePrice = LotDao::findOne([
            "idArticle" => $article->getId(),
            "dateStart" => $arrticlePriceDate
        ]);

        if ( $articlePrice == null ){
            $articlePrice = new ArticlePrice;
            $articlePrice->setDateStart( FormatUtil::sqlDateToPhpDate($values["offers_startDate"]));
            $articlePrice->setArticle($article);
            $articlePrice->setPrice(1.2 * (float)$values["offers_price"]);
            ArticlePriceDao::save($articlePrice);
        }

        $lot = LotDao::findOne([
            "idArticle" => $article->getId(),
            "orderNumberSupplier" => $values["orders_number"]
        ]);
        if ( $lot == null ){
            $lot = new Lot();
            $lot->setArticle($article);
            $lot->setOrderNumberSupplier($values["orders_number"]);
            $lot->setUnitCost($values["offers_price"]);
            $lot->setDateReception( FormatUtil::sqlDateToPhpDate($values["orders_dateDelivery"]));
            $lot->setQuantity($values["ordersArticle_quantity"]);
            LotDao::saveOrUpdate($lot);
        }

        $importation = ImportationDao::findOne([
            "idArticle" => $article->getId(),
            "orderNumberSupplier" => $values["orders_number"]
        ]);

        if ( $importation == null ){
            $importation = new Importation;
            $importation->setArticle($article);
            $importation->setAdministrator(MainController::getLoggedInUser()->getAdministrator());
            $importation->setOrderNumberSupplier($values["orders_number"]);
            ImportationDao::saveOrUpdate($importation);
        }

        return $importedArticle;
    }
}
