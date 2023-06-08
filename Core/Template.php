<?php

/**
 * File: Template.php
 * Description: This file contains the Template class, responsible for rendering views and layouts.
 */

namespace Core;

use Core\Config\AppConfig;
use Exception;

class Template
{
    /**
     * @var string|null $layout The layout file to use for rendering the view.
     */
    private $layout;

    /**
     * @var string $view The view file to render.
     */
    private $view;

    /**
     * @var array $data An array to store data variables assigned to the template.
     */
    private $data;

    /**
     * Template constructor.
     *
     * @param string $view The view file to render.
     * @param string|null $layout (optional) The layout file to use. Defaults to null.
     */
    public function __construct($view, $layout = null)
    {
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Assigns a value to a key in the template's data array.
     *
     * @param string $key The key to assign the value to.
     * @param mixed $value The value to assign.
     * @return void
     */
    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Renders the view using the assigned data and specified layout (if any).
     *
     * @return void
     */
    public function render()
    {
        extract($this->data);

        if (is_null($this->layout)) {
            $this->layout = AppConfig::getDefaultLayout();
        }

        // Start output buffering to capture the view content
        ob_start();

        // Include the view file
        include_once VIEWS . "{$this->view}.php";

        // Assign the view content to the $content variable
        $content = ob_get_clean();

        // Include the layout file
        include_once LAYOUTS . "{$this->layout}.php";
    }
}