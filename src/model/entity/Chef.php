<?php
class Chef extends User{
    public function getRecipes(): array{
        return $this->getRelatedEntities("Recipe");
    }
}