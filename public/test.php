<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/util/SiteUtil.php';

//require_once "C:/dev/apps/xampp/htdocs/php/nesti_administration/src/util/../../src/util/FormatUtil.php";
/*
$r = RecipeDao::findById(2);
$c = $r->getChef();
FormatUtil::dump($c);
*/
/*$c2 = ChefDao::findById(1);

$c2->setFirstName("BOOYAKASHA");
ChefDao::saveOrUpdate($c2);
*/
$a = ArticleDao::findById(8);
FormatUtil::dump($a);
//FormatUtil::dump($a->getArticlePrices());

/*
FormatUtil::dump(
    OrdersDao::findAll(['dateCreation'=>"{MAX}"])
);*/

FormatUtil::dump(
    $a->getOrders( ['flag'=>'a', 'ORDER' => 'dateCreation ASC'])[0]
);

$newChef = new Chef();
$newChef->setLogin("dssdqsqd");
$newChef->setLastName("dddddd");
$newChef->setEmail("dddddd@ddd.cc");
ChefDao::saveOrUpdate($newChef);
FormatUtil::dump($newChef);
/*$pdo = DatabaseUtil::connect();
$sql = "INSERT INTO users (lastName,firstName,email,passwordHash,flag,dateCreation,login,address1,address2,zipCode,idCity) 
values(?,?,?,?,?,?,?,?,?,?,?)";
$q = $pdo->prepare($sql);
$values = ["dddddd",NULL,NULL,NULL,NULL,NULL,"dssdqsqd",NULL,NULL,NULL,NULL];
$r = $q->execute($values);*/

//PopulateUtil::populate();