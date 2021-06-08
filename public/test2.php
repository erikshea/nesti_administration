<?php

use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/util/SiteUtil.php';

$brienne = UsersDao::findOne(["login" => "brienne"])->getChef();
FormatUtil::dump($brienne);

FormatUtil::dump($brienne->getRecipes());
FormatUtil::dump($brienne->getOrders());

$newestRecipe=RecipeDao::findOne(["ORDER" => "dateCreation DESC"]);

$recipes = RecipeDao::findOne([
    "portions >" => 4,
    "DAY(dateCreation)" => 2,
    "flag" => 'a'
]);

FormatUtil::dump($recipes);


$grade = new Grades(); // Créons une nouvelle note
$grade->setUser($brienne); // Utilisateur qui note
$grade->setRecipe($newestRecipe); // Recette notée

$grade->setRating(2);
GradesDao::saveOrUpdate($grade);


FormatUtil::dump($brienne->getGradedRecipes());
