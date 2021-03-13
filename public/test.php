<?php

use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

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
/*
 FormatUtil::dump(
    $_SERVER['SERVER_NAME']
 );



$test = ArticleDao::findById(8);
 FormatUtil::dump($test->getOrders(["ORDER"=>"dateCreation ASC"]));

 FormatUtil::dump($test->getOrders(["ORDER"=>"dateCreation DESC"]));*/
// FormatUtil::dump(
//     OrdersDao::findAll(['HOUR(dateCreation) >'=>5,'HOUR(dateCreation) <'=>15, 'ORDER'=>'dateCreation'])
// );

// FormatUtil::dump(
//     $a->getOrders( ['flag'=>'a', 'ORDER' => 'dateCreation ASC'])[0]
// );
//PopulateUtil::populate();
// $newChef = new Chef();
// $newChef->setLogin("dssdqsqd");
// $newChef->setLastName("dddddd");
// $newChef->setEmail("dddddd@ddd.cc");
// ChefDao::saveOrUpdate($newChef);
// FormatUtil::dump($newChef);
/*$pdo = DatabaseUtil::getConnection()();
$sql = "INSERT INTO users (lastName,firstName,email,passwordHash,flag,dateCreation,login,address1,address2,zipCode,idCity) 
values(?,?,?,?,?,?,?,?,?,?,?)";
$q = $pdo->prepare($sql);
$values = ["dddddd",NULL,NULL,NULL,NULL,NULL,"dssdqsqd",NULL,NULL,NULL,NULL];
$r = $q->execute($values);*/


// $t = RecipeDao::findAll('ORDER'=>'recipePosition');

// FormatUtil::dump($t->getIngredientRecipes(['ORDER'=>'recipePosition ASC']));



// $t = ProductDao::findOneBy('name','tesdsssdt');
// $t->makeIngredient();


/*$t->makeIngredient();
FormatUtil::dump("qq");

FormatUtil::dump($t);

*/
/*$p = new Product();

$p->setName("tesddddsssdt");
ProductDao::save($p);
FormatUtil::dump($p);

$p->makeIngredient();*/


// $test = new IngredientRecipe;

// $test->setIdIngredient(17);
// $test->setIdRecipe(2);
// $test->setIdUnit(3);
// $test->setQuantity(322222);
// IngredientRecipeDao::saveOrUpdate($test);



//FormatUtil::dump(RecipeDao::findAll(["INDEXBY"=>"name"]));


 // $newChef = new Chef();

//$newChef = ChefDao::findOneBy("login","ZZZZaaaa");
/*$newChef->setLogin("SQDQSDSAQQSD");
$newChef->setLastName("aDSQSQDSQDSQDSQDa");
$newChef->setEmail("a@aadddDddddddddaZ.cc");
ChefDao::saveOrUpdate($newChef);
FormatUtil::dump($newChef);*/

//FormatUtil::dump(ChefDao::findAll(["INDEXBY"=>"lastName"]));

// $test = UsersDao::findById(3);
// $r = $test->getRecipes(["ORDER"=>"dateCreation DESC"]);

// $t2 = $test->getRecipes(["ORDER"=>"dateCreation DESC"])[0]->getName();
// FormatUtil::dump($t2);

// $queryOptions=[];
// $productIds = [];
// FormatUtil::dump($productIds);
// $articles = ArticleDao::findAll(["idProduct IN" => "(" . implode(",", $productIds) . ")"]);
// FormatUtil::dump(["idProduct IN " => "(" . implode(",", $productIds) . ")"]);
// FormatUtil::dump($articles);


// $sql = "SHOW INDEX FROM IngredientRecipe WHERE Key_name = 'PRIMARY'";
// $pdo = DatabaseUtil::getConnection();
// $request = $pdo->prepare($sql);
// $request->execute();
// FormatUtil::dump($request->fetchAll(PDO::FETCH_ASSOC));


// $r = RecipeDao::findById(2);

// var_dump($r);
/*$r = RecipeDao::findById(2);
$ing = $r->getIngredients();

FormatUtil::dump($ing);
*/
/*

$a = ArticleDao::findById(1);
$o = $a->getOrders();

FormatUtil::dump($o);
*/

/*
$t = new Importation();
$t->setIdAdministrator(4);
$t->setIdArticle(7);
$t->setIdSupplierOrder(156);
ImportationDao::saveOrUpdate($t);*//*
FormatUtil::dump(
    ConnectionLogDao::findAll(["ORDER"=>"HOUR(dateConnection) ASC"])
);*/
/*$date = new DateTime;
$date->add(DateInterval::createFromDateString('-10 days'));


$o = OrdersDao::findAll(["dateCreation >" => $date->format('Y-m-d H:i:s')]);
*/
/*
$date = new DateTime;
$date->add(DateInterval::createFromDateString('-10 days'));
$day = intval($date->format('d'));
$ordersByDays = [];
*/
/*
$startDate = new DateTime;
$startDate->add(DateInterval::createFromDateString("-10 days"));

for ($i = 0; $i < 10; $i++) {
   $date = new DateTime;
   $date->add(DateInterval::createFromDateString("-{$i} days"));
   $day = intval($date->format('d'));
   $ordersByDays[$day] = OrdersDao::findAll(["dateCreation >" => $startDate->format('Y-m-d H:i:s'),"DAY(dateCreation)" =>$day]);
}

$connectionsByIdUser = ConnectionLogDao::findAll(["INDEXBY"=>"idUsers"]);

// sort by number of connections for each user id
usort($connectionsByIdUser, function ($v1, $v2) {
   return count($v2) <=> count($v1);
});

// get corresponding user for each group of connection logs
$usersWithMostConnections = array_map(function ($v) {
   return $v[0]->getUser();
},$connectionsByIdUser);

$usersWithMostConnections = array_slice($usersWithMostConnections, 0, 10);


$ordersByTotal = OrdersDao::findAll();

usort($ordersByTotal, function ($o1, $o2) {
   return $o2->getTotal() <=> $o1->getTotal();
});

$ordersByTotal = array_slice($ordersByTotal,0,3);


$chefsByRecipes = ChefDao::findAll();

usort($chefsByRecipes, function ($o1, $o2) {
   return count($o2->getRecipes()) <=> count($o1->getRecipes());
});

$chefsByRecipes = array_slice($chefsByRecipes,0,10);


$recipesByGrade =  RecipeDao::findAll();

usort($recipesByGrade, function ($r1, $r2) {
   // average grade could be null if recipe has no grade, so use null coalescing operator with 0 if null
   return ($r2->getAverageGrade() ?? 0) <=> ($r1->getAverageGrade() ?? 0);
});

$recipesByGrade = array_slice($recipesByGrade,0,10);



$articlesOutOfStock =  array_filter(ArticleDao::findAll(), function($a){ return $a->getStock() == 0; });

*/
//FormatUtil::dump( $recipesByGrade );
//FormatUtil::dump( OrdersDao::findAll(["dateCreation >" => $date->format('Y-m-d H:i:s'),"DAY(dateCreation)"=>4]));
/*
$startDate = new DateTime;
$startDate->add(DateInterval::createFromDateString("-10 days"));

$soldTotalByDay = [];
$purchasedTotalByDay = [];

for ($i = 9; $i >= 0; $i--) {
   $date = new DateTime;
   $date->add(DateInterval::createFromDateString("-{$i} days"));
   $day = intval($date->format('d'));
   $orders = OrdersDao::findAll(["dateCreation >" => $startDate->format('Y-m-d H:i:s'), "DAY(dateCreation)" => $day, "flag"=>"a"]);
   $lots = LotDao::findAll(["dateReception >" => $startDate->format('Y-m-d H:i:s'), "DAY(dateReception)" => $day]);
   //        $purchaseTotal = 0;
   
   $soldTotal = 0;
   foreach ($orders as $order) {
         $soldTotal += $order->getTotal();
   }
   $soldTotalByDay[] = $soldTotal;

   $purchasedTotal = 0;
   foreach ($lots as $lot) {
      $purchasedTotal += $lot->getSubTotal();
   }

   $purchasedTotalByDay[] = $purchasedTotal;
}


FormatUtil::dump($purchasedTotalByDay);

*/
/*
$connectionsByIdUser = ConnectionLogDao::findAll(["INDEXBY" => "idUsers"]);
//  sort by number of connections for each user id
usort($connectionsByIdUser, function ($v1, $v2) {
    return count($v2) <=> count($v1);
});
*/
$articlesOutOfStock =  array_filter(ArticleDao::findAll(), function($a){ return $a->getStock() == 0; });
FormatUtil::dump($articlesOutOfStock);

/*
foreach ( $chefsByRecipes as $chef ){
   FormatUtil::dump($chef);
   FormatUtil::dump(count($chef->getRecipes()));
}


FormatUtil::dump( $chefsByRecipes );
*/
//FormatUtil::dump($ordersByDays);