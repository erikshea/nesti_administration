<?php

class ArticleDao extends BaseDao{
 
    protected static  $tableName = "article";
    protected static  $entityClass = "Article";
    protected static  $entityPrimaryKey = "id_article";

}