<?php
namespace Core\Database\Repositories;

use Core\Database\Repositories\Repository;
use Core\Database\MySqlConnection;
use Core\Database\QueryBuilder;
use Admin\Models\UserSession;

class SessionRepo extends Repository
{
    public function __construct()
    {
        $this->table = 'sessions';
        $this->pdo = MySqlConnection::getConnection();
        $this->querybuilder = new QueryBuilder($this->table, $this->pdo);
    }

    public function findBy(UserSession $user ,$value, $col = 'id', $limit=1, $operator = '=')
    {
        $data = $this->querybuilder->select()
                                    ->where($col, $operator, $value)
                                    ->limit($limit)
                                    ->get();
        if($data){
            $user->setId($data->id);
            $user->setToken($data->hash);
            $user->setUserId($data->userID);
        }
        MySqlConnection::disconnect(); 
        return $data;
    }
    
    public function save(UserSession $user)
    { 
        $data = ['userId'  => $user->getUserId(),
                 'hash'  => $user->getToken(),];
        $data = $this->querybuilder->insert($data);
        MySqlConnection::disconnect(); 
        return $data; 
    } 
    
    public function update(UserSession $user, $data)
    { 
        $data = ['userId'  => $user->getUserId(),
                 'hash'  => $user->getToken(),];
        $data = $this->querybuilder->update($data);
        MySqlConnection::disconnect(); 
        return $data;  
    }
}