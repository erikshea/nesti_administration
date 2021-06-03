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
            'sellingPrice' => $entity->getSellingPrice(),
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
            $articles = ArticleDao::findAll(["idProduct IN " => "(" . implode(",", $productIds) . ")", 'flag'=>'a']);
        } else {
            $articles=[];
        }
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
}
