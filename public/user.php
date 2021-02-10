<?php
require_once __DIR__ . '/../src/util/SiteUtil.php';

// $user = UsersDao::findOneBy('login','test');
// $user->makeChef();
// $user->makeAdministrator();
// $user->makeModerator();

// FormatUtil::dump($user->isModerator());



$u = new Administrator();
$u->setLogin("ad");
$u->setLastName("AAAAAAAAA");
$u->setEmail("AAAAAAA@ddd.cc");
AdministratorDao::saveOrUpdate($u);
FormatUtil::dump($u);


$u = new Chef();
$u->setLogin("ch");
$u->setLastName("CCC");
$u->setEmail("CCC@ddd.cc");
ChefDao::saveOrUpdate($u);
FormatUtil::dump($u);

$u = new Moderator();
$u->setLogin("mo");
$u->setLastName("MM");
$u->setEmail("MM@ddd.cc");
ModeratorDao::saveOrUpdate($u);
FormatUtil::dump($u);