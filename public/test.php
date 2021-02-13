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

/*
$test = new IngredientRecipe;

$test->setIdIngredient(2);
$test->setIdRecipe(2);
$test->setIdUnit(3);
IngredientRecipeDao::save($test);*/



FormatUtil::dump(RecipeDao::findAll(["INDEXBY"=>"name"]));