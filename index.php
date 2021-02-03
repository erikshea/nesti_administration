<?php
// Dummy file to simulate .htaccess behavior in apache-less dev environment.
// Never called in prod (.htaccess only allows access to /public).
require_once dirname( __FILE__ ) . '/public/index.php';