<?php
namespace Core\Database\Repositories;

use App\Models\Category;
use Core\Database\Repositories\Repository;
use App\Models\Post;
use Core\Database\MySqlConnection;
use Core\Database\QueryBuilder;
use Exception;

class PostRepo extends Repository
{
    public function __construct()
    {
        $this->table = 'posts';
        $this->pdo = MySqlConnection::getConnection();
        $this->querybuilder = new QueryBuilder($this->table, $this->pdo);
    }

    public function findBy(Post $post, $value, $col = 'postId', $limit=1, $operator = '=')
    {
        $data = $this->querybuilder->select()
                                    ->where($col, $operator, $value)
                                    ->limit($limit)
                                    ->get();
        if ($data){
            $post->setId($data->postId);
            $post->setTitle($data->postTitle);
            $post->setSlug($data->postSlug);
            $post->setBody($data->postBody);
            $post->setCatId($data->catId);
            $post->setUserId($data->userId);
            $post->setCreateDate($data->postCreatedAt);
            $post->setUpdateDate($data->postUpdatedAt);
            $post->setImage($data->postImage);
            $post->setStatus($data->postStatus);
        }
        return $data;
    }
    
    
    public function save(Post $post)
    { 
        $data = ['postTitle'  => $post->getTitle(),
                 'postSlug' => $post->getSlug(),
                 'userId' => $post->getUserId(),
                 'postBody' => $post->getBody(),
                 'postImage' => $post->getImage(),
                 'postId' => $post->getId(),
                 'catId' => $post->getCatId(),
                 'postCreatedAt' => date('Y-m-d H:i:s')];
        $data = $this->querybuilder->insert($data);
        return $data; 
    } 
    
    public function update(Post $post, $value, $col = 'postId', $operator = '=')
    { 
        $data = ['postTitle'  => $post->getTitle(),
                'postSlug' => $post->getSlug(),
                'postBody' => $post->getBody(),
                'postImage' => $post->getImage(),
                'postStatus' => $post->getStatus(),
                'catId' => $post->getCatId(),
                'postUpdatedAt' => date('Y-m-d H:i:s')];
        
        $data = $this->querybuilder->where($col, $operator, $value)
                                    ->update($data);
        return $data;  
    }
    

    // public function postsJoinUser(){
    //     $data = $this->querybuilder->select()
    //                                 ->innerJoin('users', 'userId', 'id');
    //     return $data;
    // }

    public function postsJoinCat(){
        $data = $this->querybuilder->select(['posts.*, categories.*'])
                                    ->innerJoin('categories', 'catId', 'catId')
                                    ->getAll();
        return $data;
    }

    public function findByJoinCat(Post $post,Category $cat, $value, $col = 'postId', $limit=1, $operator = '=')
    {
        $data = $this->querybuilder->select(['posts.*, categories.*'])
                                    ->innerJoin('categories', 'catId', 'catId')
                                    ->where('posts.' . $col, $operator, $value)
                                    ->limit($limit)
                                    ->get();

        if ($data){
            $post->setId($data->postId);
            $post->setTitle($data->postTitle);
            $post->setSlug($data->postSlug);
            $post->setBody($data->postBody);
            $post->setCatId($data->catId);
            $post->setUserId($data->userId);
            $post->setCreateDate($data->postCreatedAt);
            $post->setUpdateDate($data->postUpdatedAt);
            $post->setImage($data->postImage);
            $post->setStatus($data->postStatus);
            $cat->setName($data->catName);
            $cat->setDesc($data->catDesc);
        } else {
            throw new Exception('no available data');
        }
        return $data;
        
    }
 

    // public function postsJoinUserCat(){
    //     $data = $this->querybuilder->select()
    //                                 ->innerJoin('cats', 'catId', 'id')
    //                                 ->innerJoin('users', 'userId', 'id')
    //                                 ->getAll();

    //     return $data;
    // }
}