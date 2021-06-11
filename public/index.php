<?php
require_once __DIR__ . '/../src/util/SiteUtil.php';
if ( ApplicationSettings::get("environment") == "dev"){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

SiteUtil::openSession();
(new Dispatcher)->dispatch();