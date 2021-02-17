<?php

class ArticleController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }

    public function actionGetOrderItemsAjax(){
        $order = OrdersDao::findById($_POST["idOrders"]);

        $orderItems = array_map( function($ol) {
            return [
                'idArticle' => $ol->getArticle()->getId(),
                'quantity' => $ol->getQuantity(),
                'unitName' => $ol->getArticle()->getUnit()->getName(),
                'articleName' => $ol->getArticle()->getProduct()->getName()
            ];
        }, $order->getOrderLines());

        echo json_encode(['orderItems' => $orderItems]);
    }

    public function actionEdit()
    {
        $entity = $this->getEntity();


        $formBuilder = new EntityFormBuilder($entity);

        $this->templateVars['assets']['js'][] = [
            'src'=>"article-edit.js",
            "type" => "text/babel",
            "addLast" => true
        ];


        if ( !empty($_POST[$this->getEntityClass()]) ) { // if we arrived here by way of the submit button in the edit view
            $formBuilder->setFormData($_POST[$this->getEntityClass()]);

            if ($formBuilder->isValid()) {
                if ( $_POST[$this->getEntityClass()]["imageStatus"] == "deleted" ) {
                    $entity->setIdImage(null);
                } elseif ( $_FILES["image"]["error"] == 0 ) {
                    $image = $entity->getImage();
                    if ( $image != null ){
                        unlink ( $image->getAbsolutePath() );
                    }else {
                        $image = new Image;
                    }

                    preg_match(
                        "/(.*)\.([^\.]+)$/", // capture filename + ext
                        $_FILES["image"]["name"],
                        $matches
                    ); 
                    $image->setName($matches[1]);
                    $image->setFileExtension($matches[2]);
                    ImageDao::saveOrUpdate($image);
                    move_uploaded_file($_FILES["image"]["tmp_name"], $image->getAbsolutePath());
                    $entity->setImage($image);
                }
                $formBuilder->applyDataTo($entity);
                $entity->setChef(MainController::getLoggedInUser()->getChef());

                if ( $entity->existsInDataSource()){
                    $this->addVars([
                        "message" => "edited"
                    ]);
                } else {
                    $this->addVars([
                        "message" => "created"
                    ]);
                }
                $this->getDaoClass()::saveOrUpdate($entity);
                $this->setEntity($entity);
                // MainController::redirect();
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
