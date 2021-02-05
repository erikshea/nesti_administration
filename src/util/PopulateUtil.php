<?php 

class PopulateUtil {

    public static function populate(){
        $user = new Users;
        $user->setEmail("test@test.com");
        $user->setLogin("test");
        $user->setFirstName("tester");
        $user->setLastName("testington");
        $user->setPasswordHashFromPlaintext("1234");
        UsersDao::saveOrUpdate($user);
    }
}