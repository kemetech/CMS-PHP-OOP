<?php
namespace App\Models;

class Post
{
    private $id; 
    private $title; 
    private $body; 
    private $slug; 
    private $userId; 
    private $catId; 
    private $image; 
    private $status;
    private $createDate;
    private $updateDate;

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
     * Get the value of title
     */ 
    public function getTitle() 
    { 
        return $this->title; 
    } 

    /**
     * Set the value of title
     */ 
    public function setTitle($title) 
    { 
        $this->title = $title; 
    } 

    /**
     * Get the value of slug
     */ 
    public function getSlug() 
    { 
        return $this->slug; 
    } 

    /**
     * Set the value of slug
     */ 
    public function setSlug($slug) 
    { 
        $this->slug = $slug; 
    } 

    /**
     * Get the value of image
     */ 
    public function getImage() 
    { 
        return $this->image; 
    } 

    /**
     * Set the value of image
     */ 
    public function setImage($image) 
    { 
        $this->image = $image; 
    } 

    /**
     * Get the value of body
     */ 
    public function getBody() 
    { 
        return $this->body; 
    } 

    /**
     * Set the value of body
     */ 
    public function setBody($body) 
    { 
        $this->body = $body; 
    } 

    /**
     * Get the value of userId
     */ 
    public function getUserId() 
    { 
        return $this->userId; 
    } 

    /**
     * Set the value of userId
     */ 
    public function setUserId($userId) 
    { 
        $this->userId = $userId; 
    } 

    /**
     * Get the value of catId
     */ 
    public function getCatId() 
    { 
        return $this->catId; 
    } 

    /**
     * Set the value of catId
     */ 
    public function setCatId($catId) 
    { 
        $this->catId = $catId; 
    } 

    /**
     * Get the value of status
     */ 
    public function getStatus() 
    { 
        return $this->status; 
    } 

    /**
     * Set the value of status
     */ 
    public function setStatus($status) 
    { 
        $this->status = $status; 
    } 

    /**
     * Get the value of createDate
     */ 
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set the value of createDate
     */ 
    public function setCreateDate($createDate) 
    { 
        $this->createDate = $createDate; 
    } 

    /**
     * Get the value of updateDate
     */ 
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set the value of updateDate
     */ 
    public function setUpdateDate($updateDate) 
    { 
        $this->updateDate = $updateDate; 
    } 
}
