<?php
class Moderator extends Users{
    private $idModerator;


    public function getModeratedComments($options=[]): array{
        return $this->getRelatedEntities("Comment",$options);
    }

    public function setModeratedComments(Comment $c){
        $this->setRelatedEntity($c);
    }

    /**
     * Get the value of idModerator
     */ 
    public function getIdModerator()
    {
        return $this->idModerator;
    }

    /**
     * Set the value of idModerator
     *
     * @return  self
     */ 
    public function setIdModerator($idModerator)
    {
        $this->idModerator = $idModerator;

        return $this;
    }
}