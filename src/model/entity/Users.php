<?php

class Users extends BaseEntity{
    private $idUsers;
    private $lastName;
    private $firstName;
    private $email;
    private $passwordHash;
    private $flag;
    private $dateCreation;
    private $login;
    private $address1;
    private $address2;
    private $zipCode;
    private $idCity;

    public function getCity(): ?City{
        return $this->getRelatedEntity("City");
    }

    public function setCity(City $c){
        $this->setRelatedEntity($c);
    }


    public function getOrders(): array{
        return $this->getRelatedEntities("Orders");
    }

    public function getConnectionLogs(): array{
        return $this->getRelatedEntities("ConnectionLog");
    }

    public function getComments(): array{
        return $this->getRelatedEntities("Comment", BaseDao::FLAGS['active']);
    }

    public function getRecipes(): array{
        return $this->getIndirectlyRelatedEntities("Recipe", "Grades", BaseDao::FLAGS['active']); 
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

    /**
     * Get the value of login
     */ 
    public function getLogin()
    {
        return $this->login;
    }

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

    public function setPasswordHashFromPlaintext($plaintextPassword){
        $this->setPasswordHash(password_hash($plaintextPassword, PASSWORD_DEFAULT));
    }
    public function isPassword($plaintextPassword){
        return password_verify ( $plaintextPassword, $this->getPasswordHash() );
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
}