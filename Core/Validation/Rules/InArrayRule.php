<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class InArrayRule implements RuleInterface
{
    /**
     * Validates that a field value is one of the allowed values specified in the rule.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The value or parameter associated with the validation rule (contains allowed values separated by commas).
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        if (!in_array($value, explode(',', $ruleValue))) {
            $msg = 'The ' . $field . ' field must be one of the allowed values.';
            $err->set($msg);
        }
    }
}
