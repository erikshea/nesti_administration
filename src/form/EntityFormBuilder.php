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
        $options = array_merge([
            'value'=> EntityUtil::get( $this->getEntity(), $propertyName ) ?? ""
        ], $options);
        return parent::add($propertyName, $options);
    }


    /**
     * validateProperty
     * Loops through all validators for that property (if any), and returns a list of failed validators
     * @param  String $fieldName
     * @return Array errors found, by validator name (or empty array if none found)
     * example return array:
     * ['notEmpty'],   // error found: empty value
     */
    public function getPropertyErrors(string $propertyName, $validatorClass = 'FormBuilderValidator'){
        $propertyErrors = parent::getPropertyErrors($propertyName);
        
        if ( isset($this->propertyParameters[$propertyName]['validators']) ) {
            // Loop through each validator for that field
            foreach($this->propertyParameters[$propertyName]['validators'] as $validatorName){
                if ( method_exists('EntityPropertyValidator', $validatorName)){
                    // store error states (negated validator) with the validator name as key
                    $errored = !EntityPropertyValidator::$validatorName(
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
        $this->setPropertyParametersFromConfig();
    }

    public function getEntity(){
        return $this->entity;
    }
}