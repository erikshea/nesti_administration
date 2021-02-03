<?php

require_once 'FormatUtil.php';

$test = [ "ddbl<>ah", "f<>aff","ssssf<>affsss"];
$test = "ddbl<>ah";
FormatUtil::sanitize($test);

FormatUtil::dump( dirname( __FILE__ ));
