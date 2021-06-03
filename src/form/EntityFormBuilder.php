<?php

class EntityFormBuilder extends FormBuilder{
    protected $entity;
    protected $formData;
    protected $validatorErrorMessages = [];
    protected $propertyParameters = [];
    protected $errors = [];

    function __construct($entity) {
        $this->setEntity($entity);
    }

    public function add($propertyName, $options=[]){
        // $options = array_merge([
        //     'value'=> EntityUtil::get( $this->getEntity(), $propertyName ) ?? ""
        // ], $options);
        $type = $this->getPropertyParameters()[$propertyName]['options']['type'] ?? "";
        if ( $type == "checkbox" || $type == "radio"){
            $options["checked"] = EntityUtil::get($this->getEntity(), $propertyName);
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

    public function setEntity($entity){
        $this->formData = EntityUtil::toArray($entity);
        $this->entity = $entity;
        $this->setFormName(get_class($entity));
        //$this->setPropertyParametersFromConfig();
    }

    public function getEntity(){
        return $this->entity;
    }

    public function applyDataTo(&$entity){
        foreach ( get_class($entity)::getDaoClass()::getColumnNames() as $columnName){
            $this->applyDataElementTo($entity, $columnName);
        }
    }

    public function applyDataElementTo(&$entity, $name){
        if ( isset($this->formData[$name])){
            EntityUtil::set($entity, $name, $this->formData[$name]);
        }
    }
}