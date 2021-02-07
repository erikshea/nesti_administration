<?php

class EntityFormBuilder extends BaseFormBuilder{
    protected $entity;
    protected $validatorErrorMessages = [];
    protected $propertyParameters = [];
    protected $errors = [];

    function __construct($entity) {
        $this->entity = $entity;
      }

    /**
     * isValid
     * Looks at error array andreturns false if it contains an error
     * @return bool true if all validators passed, false if just one validator failed
     */
    public function isValid(){
        return empty($this->getAllErrors());
    }

    /**
     * validateProperty
     * Loops through all validators for that property (if any), and returns a list of failed validators
     * @param  String $fieldName
     * @return Array errors found, by validator name (or empty array if none found)
     * example return array:
     * ['notEmpty'],   // error found: empty value
     */
    public function getPropertyErrors(String $propertyName){
        $propertyErrors = [];
        
        if ( isset($this->propertyParameters[$propertyName]['validators']) ) {
            // Loop through each validator for that field
            foreach($this->propertyParameters[$propertyName]['validators'] as $validatorName){
                // store error states (negated validator) with the validator name as key
                $errored = !EntityValidator::$validatorName($this->entity,$propertyName);
                if ( $errored ) {
                    $propertyErrors[] = $validatorName;
                }
                
            }
        }

        return $propertyErrors;
    }


     /**
     * getErrors
     * validate each field, store array of failed validators
     * @return Array multidimensional array of error arrays, stored by property name.
     * Example returned array: 
     *  [
     *      'lastName'  =>  ['notEmpty' => true],
     *      'tel'       =>  ['notEmpty' => true, 'telephone' => true ],
     *      'email'     =>  ['unique' => true ],
     * ]
     */
    public function getAllErrors(): ?array{
        foreach ($this->propertyParameters as $propertyName=>$p) {
            $this->errors[$propertyName] = $this->getPropertyErrors($propertyName);
             // unset empty array if no error found,
            if (empty($this->errors[$propertyName])){ 
                unset($this->errors[$propertyName]);
            }
        }

        return $this->errors;
    }

    public function add($propertyName, $label=null){
        $vars['errors'] = [];
        foreach ( $this->getPropertyErrors($propertyName) as $validatorName) {
            $vars['errors'][] = $this->validatorErrorMessages[$validatorName];
        }
        $vars['id'] = $propertyName;

        if ( $label == null ){
            $vars['label'] = $this->propertyParameters[$propertyName]['label'];
        } else {
            $vars['label'] = $label;
        }

        require SiteUtil::toAbsolute("templates/form/standardFormRow.php");
    }

    public function getValidatorErrorMessages()
    {
        if ( $this->validatorErrorMessages == null ){
            $jsonString = file_get_contents(
                SiteUtil::toAbsolute("config/validatorErrorMessages.json")
            );
            $this->validatorErrorMessages = json_decode($jsonString,true);
        }

        return $this->validatorErrorMessages;
    }

}