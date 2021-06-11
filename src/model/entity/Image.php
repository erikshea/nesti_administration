<?php


/**
 * Image
 */
class Image extends BaseEntity{
    private $idImage;
    private $dateCreation;
    private $name;
    private $fileExtension;
    private $dateModification;

        
    /**
     * getRecipes
     * get recipes which use this image
     * @return array
     */
    public function getRecipes(): array{
        return $this->getRelatedEntities("Recipe");
    }
    
    /**
     * getArticles
     * get articles which use this image
     * @return array
     */
    public function getArticles(): array{
        return $this->getRelatedEntities("Article");
    }
    
    /**
     * getFileName
     * get file name without path
     */
    public function getFileName(){
        return $this->getFileExtension() == null ?
            null : $this->getId() . "." . $this->getFileExtension();
    }
    
    /**
     * getAbsolutePath
     * get file system path
     */
    public function getAbsolutePath(){
        return $this->getFileExtension() == null ?
            null : SiteUtil::toAbsolute("public/assets/images/content/" . $this->getFileName());
    }
    
    /**
     * getUrl
     *
     * @return void
     */
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

    
    /**
     * setFromFiles
     * set entity information from $_FILES superglobal
     * @param  mixed $fileKey
     * @return void
     */
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
