<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class IntegerRule implements RuleInterface
{
    /**
     * Validates that a field value is an integer.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The value or parameter associated with the validation rule (not used in this rule).
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $msg = 'The ' . $field . ' field must be an integer.';
            $err->set($msg);
        }
    }
}
