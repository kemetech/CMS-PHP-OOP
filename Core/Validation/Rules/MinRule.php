<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class MinRule implements RuleInterface
{
    /**
     * Validates that a field value has a minimum length.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The minimum length required for the field value.
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        if (strlen($value) < $ruleValue) {
            $msg = 'The ' . $field . ' field must have a minimum length of ' . $ruleValue . ' characters.';
            $err->set($msg);
        }
    }
}
