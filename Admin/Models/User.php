<?php
namespace Admin\Models;

class User
{
    private $id; 
    private $fname; 
    private $lname; 
    private $email; 
    private $password; 
    private $role; 
    private $status;
    private $regDate;
    private $updateDate;
    private $acl;

    /** * Get the value of id */ 
    public function getId() 
    { 
        return $this->id; 
    } 
    public function setId($id) 
    { 
        $this->id = $id; 
    } 
    
    /** * Get the value of first name */ 
    public function getFirstName() 
    { 
        return $this->fname; 
    } 

    /** * Set the value of first name */ 
    public function setFirstName($fname) 
    { 
        $this->fname = $fname; 
    } 
    
    /** * Get the value of last name */ 
    public function getLastName() 
    { 
        return $this->lname; 
    } 

    /** * Set the value of last name */ 
    public function setLastName($lname) 
    { 
        $this->lname = $lname; 
    } 

    /** * Get the value of email */ 
    public function getEmail() 
    { 
        return $this->email; 
    } 

    /** * Set the value of email */ 
    public function setEmail($email) 
    { 
        $this->email = $email; 
    } 

    /** * Get the value of password */ 
    public function getPassword() 
    { 
        return $this->password; 
    } 

    /** * Set the value of password */ 
    public function setPassword($password) 
    { 
        $this->password = $password; 
    } 

    /** * Get the value of role */ 
    public function getRole() 
    { 
        return $this->role; 
    }

    /** * Set the value of role */ 
    public function setRole($role) 
    { 
        $this->role = $role; 
    }

    /** * Get the value of status */ 
    public function getStatus() 
    { 
        return $this->status; 
    } 

    /** * Set the value of status */ 
    public function setStatus($status) 
    { 
        $this->status = $status; 
    } 

    /** * Get the value of registration date */ 
    public function getRegDate()
    {
        return $this->regDate;
    }

    /** * Set the value of registration date */ 
    public function setRegDate($regDate) 
    { 
        $this->regDate = $regDate; 
    } 

    /** * Get the value of update date */ 
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /** * Set the value of update date */ 
    public function setUpdateDate($updateDate) 
    { 
        $this->updateDate = $updateDate; 
    } 

    /** * Get the value of ACL (Access Control List) */ 
    public function getAcl() 
    { 
        return $this->acl; 
    } 

    /** * Set the value of ACL (Access Control List) */ 
    public function setAcl($acl) 
    { 
        $this->acl = $acl; 
    } 
}
