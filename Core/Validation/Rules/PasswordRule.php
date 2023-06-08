<?php

namespace Core\Validation\Rules;

use Exception;
use Core\Validation\RuleInterface;
use Core\Validation\ValidationErrors;

class PasswordRule implements RuleInterface
{
    /**
     * Validates that a field value meets the password requirements.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The additional rule value (not used in this rule).
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue)
    {
        if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z@#-_$%^&+=§!?]{8,50}$/', $value)) {
            $msg = 'The ' . $field . ' must be minimum 8 characters. <br>
            The ' . $field . ' must have at least one uppercase letter <br>
            The ' . $field . ' must have at least one lowercase letter <br>
            The ' . $field . ' must have at least one number<br>
            The ' . $field . ' must have at least one of the following special character @#-_$%^&+=§!? <br>';
            $err->set($msg);
        }
    }
}
