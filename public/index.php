<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname( __FILE__ ) . '/../src/util/SiteUtil.php';
require_once dirname( __FILE__ ) . '/../vendor/autoload.php';
SiteUtil::require("controller/UserController.php");
new UserController(); // Constructor will determine action 
