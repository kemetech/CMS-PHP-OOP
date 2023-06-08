<?php
namespace App\Models;

class Category
{
    private $id; 
    private $name; 
    private $desc; 

    /**
     * Get the value of id
     */ 
    public function getId() 
    { 
        return $this->id; 
    } 

    /**
     * Set the value of id
     */ 
    public function setId($id) 
    { 
        $this->id = $id; 
    } 
    
    /**
     * Get the value of name
     */ 
    public function getName() 
    { 
        return $this->name; 
    } 

    /**
     * Set the value of name
     */ 
    public function setName($name) 
    { 
        $this->name = $name; 
    } 

    /**
     * Get the value of desc
     */ 
    public function getDesc() 
    { 
        return $this->desc; 
    } 

    /**
     * Set the value of desc
     */ 
    public function setDesc($desc) 
    { 
        $this->desc = $desc; 
    } 
}
