<?php

use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/util/SiteUtil.php';
?>
<script>alert()</script>



/*
$user = UsersDao::findOne(["login" => "ron"]);

var_dump($user->getCity());

var_dump($user->getOrders());
*/
/*
$user->getOrders([
    "flag" => "w",
    "dateCreation >" => FormatUtil::dateTimeToSqlDate($startDate),
    "DAY(dateCreation)" => 3,
    "ORDER"=>"dateConnection DESC"
]);
*/

/*
$chef = new Chef();
$chef->setFirstName("Auguste");
$chef->setLastName("Gusteau");
$chef->setLogin("agusteau");
$chef->setEmail("auguste@gmail.com");
ChefDao::save($chef);*/

/*$chefAuguste=UsersDao::findOne(["login" => "agusteau"])->getChef()->getUser();*/
/*FormatUtil::dump($chef->getRecipes()); // Méthode de "Chef"
FormatUtil::dump($chef->getConnectionLogs()); // Méthode de "Users"*/

// trouvons la recette la plus récente
/*$newestRecipe=RecipeDao::findOne(["ORDER" => "dateCreation DESC"]);

$grade = new Grades(); // Créons une nouvelle note
$grade->setUser($chefAuguste); // Utilisateur qui note
$grade->setRecipe($newestRecipe); // Recette notée

$grade->setRating(2);
GradesDao::saveOrUpdate($grade);


FormatUtil::dump($chefAuguste->getGradedRecipes());*/
