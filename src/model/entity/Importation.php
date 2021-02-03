<?php

class Importation extends BaseEntity{
    private $idUser;
    private $idSupplierOrder;
    private $dateImportation;

    

    /**
     * Get the value of idUser
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of idSupplierOrder
     */
    public function getIdSupplierOrder()
    {
        return $this->idSupplierOrder;
    }

    /**
     * Set the value of idSupplierOrder
     *
     * @return  self
     */
    public function setIdSupplierOrder($idSupplierOrder)
    {
        $this->idSupplierOrder = $idSupplierOrder;

        return $this;
    }

    /**
     * Get the value of dateImportation
     */
    public function getDateImportation()
    {
        return $this->dateImportation;
    }

    /**
     * Set the value of dateImportation
     *
     * @return  self
     */
    public function setDateImportation($dateImportation)
    {
        $this->dateImportation = $dateImportation;

        return $this;
    }
}