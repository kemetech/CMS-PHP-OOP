<?php

/**
 * File: Upload.php
 * Description: This file contains the Upload class, responsible for handling file uploads.
 */

namespace Core;

use Admin\Auth\Session;
use Core\Config\UploadConfig;

class Upload
{
    private $allowedExtensions;
    private $allowedMimes;
    private $uploadDir;
    private $maxSize;
    private $fileName;
    private $fileSize;
    private $file;
    
    /**
     * Constructor.
     *
     * @param array $file The file array from the uploaded file.
     */
    public function __construct($file)
    {
        $this->allowedMimes = explode(',', UploadConfig::getAllowedMimeTypes());
        $this->allowedExtensions = explode(',', UploadConfig::getAllowedExtensions());
        $this->maxSize = UploadConfig::getMaxSize() * 1024 * 1024;
        $this->uploadDir = UPLOAD_DIR;
        $this->file = $file;
    }
    
    /**
     * Handles the file upload process.
     *
     * @return string|bool The generated file name if the upload is successful, false otherwise.
     */
    public function upload()
    {   
        if ($this->validateFile()) {
            $fileName = $this->generateFileName();
            $destination = $this->uploadDir . $fileName;

            if (move_uploaded_file($this->file['tmp_name'], $destination)) {
                return $fileName;
            }
        }

        return false;
    }

    /**
     * Validates the uploaded file.
     *
     * @return bool True if the file is valid, false otherwise.
     */
    private function validateFile()
    {
        if (!$this->isFileUploaded()) {
            return false;
        }

        if (!$this->isValidExtension()) {
            $err = "Error: Invalid image type. Allowed extensions are " . UploadConfig::getAllowedExtensions() . ".";
            Session::flash('postError', $err);
            return false;        
        }

        if (!$this->isValidMime()) {
            $err = "Error: Invalid image type. Allowed MIME types are " . UploadConfig::getAllowedMimeTypes() . ".";
            Session::flash('postError', $err);
            return false;        
        }

        if (!$this->isValidSize()) {
            $err = "Error: Image size exceeds the allowed limit of " . $this->maxSize . ".";
            Session::flash('postError', $err);
            return false;        
        }
        
        return true;
    }

    /**
     * Checks if a file has been successfully uploaded.
     *
     * @return bool True if the file has been uploaded, false otherwise.
     */
    private function isFileUploaded()
    {
        return isset($this->file['error']) && $this->file['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Checks if the file extension is valid.
     *
     * @return bool True if the file extension is valid, false otherwise.
     */
    private function isValidExtension()
    {
        $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $this->allowedExtensions);
    }

    /**
     * Checks if the file MIME type is valid.
     *
     * @return bool True if the file MIME type is valid, false otherwise.
     */
    private function isValidMime()
    {
        $fileMimeType = mime_content_type($this->file['tmp_name']);
        return in_array(strtolower($fileMimeType), $this->allowedMimes);
    }

    /**
     * Checks if the file size is valid.
     *
     * @return bool True if the file size is valid, false otherwise.
     */
    private function isValidSize()
    {
        return $this->file['size'] <= $this->maxSize;
    }

    /**
     * Generates a unique file name for the uploaded file.
     *
     * @return string The generated file name.
     */
    private function generateFileName()
    {
        $fileName = uniqid() . '.' . pathinfo($this->file['name'], PATHINFO_EXTENSION);
        return htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8');
    }
}
