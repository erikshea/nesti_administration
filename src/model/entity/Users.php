<?php

class Users extends BaseEntity{
    private $idUsers;
    /**
     * Get the value of idUser
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */
    public function setIdUsers($idUsers)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    private $login;
    /**
     * Get the value of login
     */ 
    public function getLogin()
    {
        return $this->login;
    }


    private $lastName;
    private $firstName;
    private $email;
    private $passwordHash;
    private $flag;
    private $dateCreation;
    private $address1;
    private $address2;
    private $zipCode;
    private $idCity;
    private $roles;
    private $authentificationToken;



    /**
     * Set the value of login
     *
     * @return  self
     */ 
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }




    public function getCity(): ?City{
        return $this->getRelatedEntity("City");
    }

    public function setCity(City $c){
        $this->setRelatedEntity($c);
    }

    public function getOrders($options=[]): array{
        return $this->getRelatedEntities("Orders",$options);
    }
    
    public function getConnectionLogs($options=[]): array{
        return $this->getRelatedEntities("ConnectionLog", $options);
    }

    public function getComments($options=['a']): array{
        return $this->getRelatedEntities("Comment", $options);
    }
        

    public function getGradedRecipes($options=['a']): array{
        return $this->getIndirectlyRelatedEntities("Recipe", "Grades", $options); 
    }

    public function getLatestConnectionDate(){
        $log = $this->getConnectionLogs(["ORDER"=>"dateConnection DESC"])[0] ?? null;

        return $log == null? null : $log->getDateConnection();
    }

    public function getLatestOrder(){
        return $this->getOrders(["ORDER"=>"dateCreation DESC"])[0] ?? null;
    }

    

    public function setPasswordHashFromPlaintext($plaintextPassword){
        $this->setPasswordHash(password_hash($plaintextPassword, PASSWORD_DEFAULT));
    }
    public function isPassword($plaintextPassword){
        return password_verify ( $plaintextPassword, $this->getPasswordHash() );
    }
    /**
     * Get the value of dateCreation
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }



    /**
     * Get the value of flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set the value of flag
     *
     * @return  self
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPasswordHash($p)
    {
        $this->passwordHash = $p;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firtName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firtName
     *
     * @return  self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of address1
     */ 
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set the value of address1
     *
     * @return  self
     */ 
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get the value of address2
     */ 
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set the value of address2
     *
     * @return  self
     */ 
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get the value of zipCode
     */ 
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set the value of zipCode
     *
     * @return  self
     */ 
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get the value of idCity
     */ 
    public function getIdCity()
    {
        return $this->idCity;
    }

    /**
     * Set the value of idCity
     *
     * @return  self
     */ 
    public function setIdCity($idCity)
    {
        $this->idCity = $idCity;

        return $this;
    }

    public function getFullName(){
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public function getChef(){
        return $this->getChildEntity("Chef");
    }

    public function makeChef(){
        return $this->makeChildEntity("Chef");
    }

    public function removeChef(){
        $chef = $this->getChef();

        if ( $chef != null && empty($chef?->getRecipes()) ){
            ChefDao::delete($chef);
        }
    }

    public function isChef(){
        return $this->getChef() != null;
    }

    public function getAdministrator(){
        return $this->getChildEntity("Administrator");
    }

    public function makeAdministrator(){
        return $this->makeChildEntity("Administrator");
    }

    public function removeAdministrator(){
        $administrator = $this->getAdministrator();

        if ( $administrator != null ) {
            AdministratorDao::delete($administrator);
        }
    }

    public function isAdministrator(){
        return $this->getAdministrator() != null;
    }

    public function getModerator(){
        return $this->getChildEntity("Moderator");
    }

    public function makeModerator(){
        return $this->makeChildEntity("Moderator");
    }

    public function removeModerator(){
        $moderator = $this->getModerator();

        if ( $moderator != null && empty($moderator?->getModeratedComments()) ){
            ModeratorDao::delete($moderator);
        }
    }

    public function isModerator(){
        return $this->getModerator() != null;
    }

    public function getRoles(){
        if ( $this->roles == null){
            $this->roles = [];

            if ( $this->isAdministrator() ){
                $this->roles[] = "administrator";
            }
    
            if ( $this->isModerator() ){
                $this->roles[] = "moderator";
            }
    
            if ( $this->isChef() ){
                $this->roles[] = "chef";
            }
        }
        return $this->roles;
    }



    public function setRoles($roles){
        if ( in_array("chef", $roles)){
            $this->makeChef();
        } else {
            $this->removeChef();
        }

        if ( in_array("moderator", $roles)){
            $this->makeModerator();
        } else {
            $this->removeModerator();
        }

        if ( in_array("administrator", $roles)){
            $this->makeAdministrator();
        } else {
            $this->removeAdministrator();
        }
    }
    

    /**
     * Get the value of authentificationToken
     */ 
    public function getAuthentificationToken()
    {
        return $this->authentificationToken;
    }

    /**
     * Set the value of authentificationToken
     *
     * @return  self
     */ 
    public function setAuthentificationToken($authentificationToken)
    {
        $this->authentificationToken = $authentificationToken;

        return $this;
    }

    public function initializeAuthentificationToken(){
        $this->setAuthentificationToken(bin2hex(random_bytes(32)));
    }


    public function delete(){

        /*if ( 
            empty($this->getComments())
            && empty($this->getChef()?->getRecipes())
            && empty($this->getModerator()?->getModeratedComments()
            && empty($this->getOrders())
            && empty($this->getConnectionLogs())  // IMPOSSIBLE, ne peut donc pas effacer l'utilisateur
            )
        ){
            $this->removeChef();
            $this->removeModerator();
            $this->removeAdministrator();
            $this->getDaoClass()::delete($this);
        } else {*/
        $this->setFlag("b");
        $this->getDaoClass()::saveOrUpdate($this);
        //}
    }
}