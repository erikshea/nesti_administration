<?php

class ConnectionLog extends BaseEntity{
    private $idUserLog;
    private $dateConnection;
    private $idUser;

    

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
     * Get the value of dateConnection
     */ 
    public function getDateConnection()
    {
        return $this->dateConnection;
    }

    /**
     * Set the value of dateConnection
     *
     * @return  self
     */ 
    public function setDateConnection($dateConnection)
    {
        $this->dateConnection = $dateConnection;

        return $this;
    }

    /**
     * Get the value of idUserLog
     */ 
    public function getIdUserLog()
    {
        return $this->idUserLog;
    }

    /**
     * Set the value of idUserLog
     *
     * @return  self
     */ 
    public function setIdUserLog($idUserLog)
    {
        $this->idUserLog = $idUserLog;

        return $this;
    }
}