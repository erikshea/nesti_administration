<?php

/**
 * EntityFormBuilder
 * Build and validates a form based on an entity
 */
class EntityFormBuilder extends FormBuilder{
    protected $entity;
    protected $formData;
    protected $validatorErrorMessages = [];
    protected $propertyParameters = [];
    protected $errors = [];

    function __construct($entity) {
        $this->setEntity($entity);
    }
    
    /**
     * add
     * adds a new field
     * @param  mixed $propertyName
     * @param  mixed $options
     * @return void
     */
    public function add($propertyName, $options=[]){

        $type = $this->getPropertyParameters()[$propertyName]['options']['type'] ?? "";

        // If checkbox or radio matches entity property, get property value to determine checked buttons
        if ($type == "checkbox" || $type == "radio" && !isset($options["checked"])){
            $options["checked"] = $this->isSubmitted() ?
                $this->formData[$propertyName]
                : EntityUtil::get($this->getEntity(), $propertyName);
        }

        return parent::add($propertyName, $options);
    }


    /**
     * validateProperty
     * Loops through all validators for that property (if any), and returns a list of failed validators
     * @param  String $propertyName
     * @param  String $validatorClass
     * @return Array errors found, by validator name (or empty array if none found)
     * 
     * example return array:
     * ['notEmpty'],   // error found: empty value
     */
    public function getPropertyErrors(string $propertyName,string $validatorClass = 'FormBuilderValidator'){
        $propertyErrors = parent::getPropertyErrors($propertyName);
        
        if ( isset($this->getPropertyParameters()[$propertyName]['validators']) ) {
            // Loop through each validator for that field
            foreach($this->getPropertyParameters()[$propertyName]['validators'] as $validatorName){
                if ( method_exists('EntityFormBuilderValidator', $validatorName) && isset($this->formData[$propertyName])){
                    // store error states (negated validator) with the validator name as key
                    $errored = !EntityFormBuilderValidator::$validatorName(
                        $this->formData[$propertyName],
                        $this->entity,
                        $propertyName
                    );
                    if ( $errored ) {
                        $propertyErrors[] = $validatorName;
                    }
                }
            }
        }

        return $propertyErrors;
    }
    
    /**
     * setEntity
     * set entity from which fields will be filled and validated
     * @param  mixed $entity
     * @return void
     */
    public function setEntity($entity){
        $this->formData = EntityUtil::toArray($entity);
        $this->entity = $entity;
        $this->setFormName(get_class($entity));
    }
    
    /**
     * getEntity
     * get entity from which fields will be filled and validated
     */
    public function getEntity(){
        return $this->entity;
    }
    
    /**
     * applyDataTo
     * apply current form's data to an entity
     * @param  mixed $entity
     * @return void
     */
    public function applyDataTo(&$entity){
        foreach ( get_class($entity)::getDaoClass()::getColumnNames() as $columnName){
            $this->applyDataElementTo($entity, $columnName);
        }
    }
    
    /**
     * applyDataElementTo
     * apply a form field's data to an entity 
     * @param  mixed $entity
     * @param  mixed $name
     * @return void
     */
    public function applyDataElementTo(&$entity, $name){
        if ( isset($this->formData[$name])){
            EntityUtil::set($entity, $name, $this->formData[$name]);
        }
    }
}