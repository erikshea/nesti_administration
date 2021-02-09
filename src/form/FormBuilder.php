<?php

class FormBuilder{
    protected $formData;
    protected $validatorErrorMessages = [];
    protected $propertyParameters = [];
    protected $errors = [];
    protected $formName = "form";

    function __construct(array $formData) {
        $this->formData = $formData;
      }

    /**
     * isValid
     * Looks at error array andreturns false if it contains an error
     * @return bool true if all validators passed, false if just one validator failed
     */
    public function isValid(): bool{
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
    public function getPropertyErrors(string $propertyName){
        $validatorClass = 'FormBuilderValidator';

        $propertyErrors = [];

        if ( isset($this->propertyParameters[$propertyName]['validators']) ) {
            // Loop through each validator for that field
            foreach($this->propertyParameters[$propertyName]['validators'] as $validatorName){
                // store error states (negated validator) with the validator name as key
                if ( method_exists('FormBuilderValidator', $validatorName)){
                    $errored = !FormBuilderValidator::$validatorName($this->formData[$propertyName]);
                    if ( $errored ) {
                        $propertyErrors[] = $validatorName;
                    }
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

    public function add($propertyName, $options=[]){
        $vars = [];
        $vars['errorMessages'] = [];

        if ( !isset($options['validation']) || $options['validation'] == true ) {
            foreach ( $this->getPropertyErrors($propertyName) as $validatorName) {
                $vars['errorMessages'][] = $this->getValidatorErrorMessages()[$validatorName];
            }
        }

        $vars['fieldName'] = $propertyName;

        $vars['icon'] = $options['icon'] ?? $this->propertyParameters[$propertyName]['icon'] ?? null;
        $vars['formName'] = $this->getFormName();
        $vars['value'] = $options['value'] ?? $this->formData[$propertyName] ?? "";

        $vars['type'] = $this->propertyParameters[$propertyName]['type'] ?? "text";

        $vars['required'] = $options['required'] ?? in_array("notEmpty", $this->propertyParameters[$propertyName]['validators']);


        if ( isset($options['label']) ){
            $vars['label'] = $options['label'];
        } else {
            $vars['label'] = $this->propertyParameters[$propertyName]['label'];
        }

        require SiteUtil::toAbsolute("templates/form/standardFormRow.php");

        return $this;
    }


    public function getValidatorErrorMessages()
    {
        if ( $this->validatorErrorMessages == null ){
            $jsonString = file_get_contents(
                SiteUtil::toAbsolute("config/form/validatorErrorMessages.json")
            );
            $this->validatorErrorMessages = json_decode($jsonString,true);
        }

        return $this->validatorErrorMessages;
    }

    protected function setFormDataValue($key,$value){
        $this->formData[$key] = $value;
    }

    protected function setFormData($formData){
        $this->formData = $formData;
    }
    
    

    /**
     * Get the value of propertyParameters
     */ 
    public function getPropertyParameters()
    {
        return $this->propertyParameters;
    }

    /**
     * Set the value of propertyParameters
     *
     * @return  self
     */ 
    public function setPropertyParameters($propertyParameters)
    {
        $this->propertyParameters = $propertyParameters;

        return $this;
    }


    public function setPropertyParametersFromConfig($key=null){
        $jsonString = file_get_contents(
            SiteUtil::toAbsolute("config/form/formParameters.json")
        );
        $allParameters = json_decode($jsonString,true);
        
        $this->propertyParameters = $allParameters[$key ?? $this->getFormName()];

        return $this;
    }

    /**
     * Get the value of formName
     */ 
    public function getFormName()
    {
        return $this->formName;
    }

    /**
     * Set the value of formName
     *
     * @return  self
     */ 
    public function setFormName($formName)
    {
        $this->formName = $formName;

        return $this;
    }
}