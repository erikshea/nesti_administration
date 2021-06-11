<?php

/**
 * Moderator
 */
class Moderator extends Users{
    private $idModerator;

    
    /**
     * getModeratedComments
     * get all comments moderated by this moderator
     * @param  mixed $options
     * @return array
     */
    public function getModeratedComments($options=[]): array{
        return $this->getRelatedEntities("Comment",$options);
    }
    
    /**
     * setModeratedComment
     * define a comment as moderated by this moderator 
     * @param  mixed $c
     * @return void
     */
    public function setModeratedComment(Comment $c){
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