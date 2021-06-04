<?php

class FormBuilder{
    protected $formData;
    protected $validatorErrorMessages = [];
    protected $propertyParameters;
    protected $errors = [];
    protected $formName = "form";

    function __construct(array $formData = null) {
        if ($formData == null){
            $formData = $_POST;
        }
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
     //   $validatorClass = 'FormBuilderValidator';

        $propertyErrors = [];

        if ( isset($this->getPropertyParameters()[$propertyName]['validators']) ) {
            // Loop through each validator for that field
            foreach($this->getPropertyParameters()[$propertyName]['validators'] as $validatorName){
                $errored = false;

                $fieldValue = isset($this->getPropertyParameters()[$propertyName]['options']['inputName']) ?
                    ( $_POST[$this->getPropertyParameters()[$propertyName]['options']['inputName']] ?? false ):
                    ( $this->formData[$propertyName] ?? false );

                if ( ($this->getPropertyParameters()[$propertyName]['options']['mode'] ?? null) == "optional" && empty($fieldValue)) {
                    $errored = false;
                }else if (preg_match('/(.*)\((.*)\)/', $validatorName, $matches)){
                    $validatorName = $matches[1];
                    $fieldNameParameter = $matches[2];
                    if ( method_exists('FormBuilderValidator', $validatorName) && $fieldValue !== false ){
                        $errored = !FormBuilderValidator::$validatorName($fieldValue, $this->formData[$fieldNameParameter]);
                    }
                } elseif ( method_exists('FormBuilderValidator', $validatorName) && $fieldValue !== false ){
                    $errored = !FormBuilderValidator::$validatorName($fieldValue);
                }
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
        foreach ($this->getPropertyParameters() as $propertyName=>$p) {
            $this->errors[$propertyName] = $this->getPropertyErrors($propertyName);
             // unset empty array if no error found,
            if (empty($this->errors[$propertyName])){ 
                unset($this->errors[$propertyName]);
            }
        }

        return $this->errors;
    }

    public function add($propertyName, $options=[]){
        $validators = $this->getPropertyParameters()[$propertyName]['validators'] ?? [];

        $defaultOptions = [
            'fieldName'=>$propertyName,
            'class' => '',
            'icon' => null,
            'formName' => $this->getFormName(),
            'value' => $this->formData[$propertyName] ?? '',
            'type' => 'text',
            'required' => in_array("notEmpty", $validators),
            "template" => 'standardFormRow',
            "assetsUrl" => SiteUtil::url("public/assets/"),
            "version" => ApplicationSettings::get("version")
        ];


        $vars = array_merge(
            $defaultOptions, 
            $this->getPropertyParameters()[$propertyName]['options'],
            $options // method parameter options will overwrite the previous two for identical keys
        );

        $vars['errorMessages'] = [];

        if ( !isset($vars['validation']) || $vars['validation'] == true ) {
            foreach ( $this->getPropertyErrors($propertyName) as $validatorName) {
                $vars['errorMessages'][] = $this->getValidatorErrorMessages()[$validatorName] ?? "";
            }
        }

        switch($vars['type']) {
            case '%image%':
                $vars["placeHolder"] = SiteUtil::url("public/assets/") . $vars["placeHolder"];
                $vars["type"] = "file";
                $vars["template"] = "imageUpload";
                $vars["initialBackground"] = $vars["initialBackground"] ?? $vars["placeHolder"];
                break;
            case '%csrf%':
                $vars["type"] = "hidden";
                $vars["value"] = SecurityUtil::getCsrfToken();
                break;
        }

        require SiteUtil::toAbsolute("templates/form/{$vars["template"]}.php");

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


    public function setFormData($formData){
        $this->formData = $formData;
    }
    public function addFormData($formData){
        $this->formData = array_merge($this->formData, $formData);
    }
    
    public function getFormData(){
        return $this->formData;
    }

    /**
     * Get the value of propertyParameters
     */ 
    public function getPropertyParameters()
    {
        if ( $this->propertyParameters == null ){
            $this->setPropertyParametersFromConfig();
        }

        return $this->propertyParameters ?? [];
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
    /**
     * initializes the current form's field parameters from a JSON config file
     *
     * @return  self
     */ 
    public function setPropertyParametersFromConfig($key=null){
        $jsonString = file_get_contents(
            SiteUtil::toAbsolute("config/form/formParameters.json")
        );
        $allParameters = json_decode($jsonString,true);
        
        $this->propertyParameters = array_merge(
            $allParameters["*"] ?? [], // global parameters
            $allParameters[$key ?? $this->getFormName()]  ?? []
        );

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