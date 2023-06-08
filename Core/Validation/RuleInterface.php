<?php

namespace Core\Validation;

use Core\Validation\ValidatoionErrors;

interface RuleInterface
{
    /**
     * Validates a field value against a specific rule.
     *
     * @param ValidationErrors $err The validation errors object to store any validation errors.
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param mixed $ruleValue The value or parameter associated with the validation rule.
     * @return void
     */
    public function validate(ValidationErrors $err, $field, $value, $ruleValue);
}
