<?php

use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/util/SiteUtil.php';

$string = "Dans une casserole porter à ébullition l&#39;eau, le sucre et les gousses de vanille fendues et grattées.";

$t = htmlspecialchars_decode ("Dans une casserole porter à ébullition l&#39;eau, le sucre et les gousses de vanille fendues et grattées.");
$t2 = htmlentities ("Dans une casserole porter à ébullition l&#39;eau, le sucre et les gousses de vanille fendues et grattées.");
$t3 = htmlentities ("Dans une casserole porter à ébullition l&#39;eau, le sucre et les gousses de vanille fendues et grattées.");
$t3 = html_entity_decode($string, ENT_QUOTES | ENT_XML1, 'UTF-8');
echo $t ;


$articlesOutOfStock =  array_filter(ArticleDao::findAll(), function($a){ return $a->getStock() == 0; });
FormatUtil::dump($articlesOutOfStock);

