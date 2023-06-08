<?php

namespace Core\Validation;

class ValidationErrors
{
    protected $errors = [];

    /**
     * Add an error message to the collection.
     *
     * @param string $msg The error message to add.
     */
    public function set($msg)
    {
        $this->errors[] = $msg;
    }

    /**
     * Get all the error messages.
     *
     * @return array The array of error messages.
     */
    public function get()
    {
        return $this->errors;
    }
}
