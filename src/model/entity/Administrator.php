<?php
class Administrator extends User{
  
    public function getImportations(): array{
        return $this->getRelatedEntities("Importation");
    }

}