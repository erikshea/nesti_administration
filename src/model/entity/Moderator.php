<?php
class Moderator extends User{
  
    public function getComment(): array{
        return $this->getRelatedEntities("Comment");
    }

}