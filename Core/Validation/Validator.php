<?php

namespace Core\Validation;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class Validator
{
    protected $data;
    protected $rules;
    protected $validationErrors;

    /**
     * Create a new Validator instance.
     *
     * @param array $data The data to be validated.
     * @param array $rules The validation rules to be applied.
     */
    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validationErrors = new ValidationErrors;
    }

    /**
     * Validate the data based on the specified rules.
     *
     * @return bool True if validation passes, false otherwise.
     * @throws Exception When encountering an invalid validation rule or a missing rule class.
     */
    public function validate()
    {
        foreach ($this->rules as $field => $rule) {
            $rules = explode('|', $rule);

            foreach ($rules as $singleRule) {

                $ruleParts = explode(':', $singleRule);
                $ruleName = $ruleParts[0];
                $ruleValue = isset($ruleParts[1]) ? $ruleParts[1] : null;

                $ruleClassName = '\\Core\\Validation\\Rules\\' .  ucfirst($ruleName) . 'Rule';

                if (class_exists($ruleClassName)) {
                    $ruleObject = new $ruleClassName();

                    if (!$ruleObject instanceof RuleInterface) {
                        throw new Exception('Invalid validation rule: ' . $ruleName);
                    }

                    $value = isset($this->data[$field]) ? $this->data[$field] : null;
                    $ruleObject->validate($this->validationErrors, $field, $value, $ruleValue);

                } else {
                    throw new Exception($ruleClassName . ' class not found');
                }
            }
        }
        return empty($this->validationErrors->get());
    }

    /**
     * Get the validation errors.
     *
     * @return array The array of validation errors.
     */
    public function getErrors()
    {
        return $this->validationErrors->get();
    }
}
