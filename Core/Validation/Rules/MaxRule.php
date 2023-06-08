<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class MaxRule implements RuleInterface
{
    /**
     * Validates that a field value does not exceed a maximum length.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The maximum length allowed for the field value.
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        if (strlen($value) > $ruleValue) {
            $msg = 'The ' . $field . ' field must not exceed a maximum length of ' . $ruleValue . ' characters.';
            $err->set($msg);
        }
    }
}
