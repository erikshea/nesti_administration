<?php
class Administrator extends User{
    private $idAdministrator;


    public function getImportations(): array{
        return $this->getRelatedEntities("Importation"); // TODO : many to many
    }


    /**
     * Get the value of idAdministrator
     */ 
    public function getIdAdministrator()
    {
        return $this->idAdministrator;
    }

    /**
     * Set the value of idAdministrator
     *
     * @return  self
     */ 
    public function setIdAdministrator($idAdministrator)
    {
        $this->idAdministrator = $idAdministrator;

        return $this;
    }
}