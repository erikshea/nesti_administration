<?php
class Image extends BaseEntity{
    private $idImage;
    private $dateCreation;
    private $name;
    private $fileExtension;
    private $dateModification;

    
    public function getRecipes(): array{
        return $this->getRelatedEntities("Recipe");
    }

    public function getArticles(): array{
        return $this->getRelatedEntities("Article");
    }

    public function getFileName(){
        return $this->getFileExtension() == null ?
            null : $this->getId() . "." . $this->getFileExtension();
    }

    public function getAbsolutePath(){
        return $this->getFileExtension() == null ?
            null : SiteUtil::toAbsolute("public/assets/images/content/" . $this->getFileName());
    }

    public function getUrl(){
        return SiteUtil::url("public/assets/images/content/" . $this->getFileName())
            . "?dateModification=" . urlencode($this->getDateModification());
    }
 
    /**
     * Get the value of fileExtension
     */ 
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Set the value of fileExtension
     *
     * @return  self
     */ 
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Get the value of idImage
     */ 
    public function getIdImage()
    {
        return $this->idImage;
    }

    /**
     * Set the value of idImage
     *
     * @return  self
     */ 
    public function setIdImage($idImage)
    {
        $this->idImage = $idImage;

        return $this;
    }

    /**
     * Get the value of dateModification
     */ 
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set the value of dateModification
     *
     * @return  self
     */ 
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }


    public function setFromFiles($fileKey)
    {
        if ( file_exists($this->getAbsolutePath()) ){
            unlink ( $this->getAbsolutePath() );
        }

        preg_match(
            "/(.*)\.([^\.]+)$/", // capture filename + ext
            $_FILES[$fileKey]["name"],
            $matches
        ); 
        $this->setName($matches[1]);
        $this->setFileExtension($matches[2]);
        $this->setDateModification(FormatUtil::currentSqlDate());
        ImageDao::saveOrUpdate($this);
        move_uploaded_file($_FILES[$fileKey]["tmp_name"], $this->getAbsolutePath());
    }
}
