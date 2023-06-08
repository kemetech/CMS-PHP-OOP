<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class StringRule implements RuleInterface
{
    /**
     * Validates that a field value contains only letters, numbers, spaces, hyphens, and commas.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The additional rule value (not used in this rule).
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        $allowedChars = '/^[a-zA-Z0-9\s\-.,!]+$/';

        if (!preg_match($allowedChars, $value)) {
            $msg = 'The field ' . $field . ' should only contain letters, numbers, spaces, hyphens, and commas.';
            $err->set($msg);
        }
    }
}
