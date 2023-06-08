<?php

namespace Core\Database\Repositories;

use Core\Database\Repositories\Repository;
use Core\Database\MySqlConnection;
use Core\Database\QueryBuilder;
use Admin\Models\User;

class UserRepo extends Repository
{
    public function __construct()
    {
        $this->table = 'users';
        $this->pdo = MySqlConnection::getConnection();
        $this->querybuilder = new QueryBuilder($this->table, $this->pdo);
    }

    /**
     * Finds a user by a specific value in a given column.
     *
     * @param User $user The user object to populate with the retrieved data.
     * @param mixed $value The value to match in the column.
     * @param string $col The column to search for the value.
     * @param int $limit The maximum number of records to retrieve (default: 1).
     * @param string $operator The operator to use in the comparison (default: '=').
     * @return mixed The retrieved data.
     */
    public function findBy(User $user, $value, $col = 'userId', $limit = 1, $operator = '=')
    {
        $data = $this->querybuilder->select()
            ->where($col, $operator, $value)
            ->limit($limit)
            ->get();

        if ($data) {
            $user->setFirstName($data->userFirstName);
            $user->setLastName($data->userLastName);
            $user->setId($data->userId);
            $user->setEmail($data->userEmail);
            $user->setPassword($data->userPass);
            $user->setRole($data->userRole);
            $user->setStatus($data->userStatus);
            $user->setRegDate($data->userCreatedAt);
            $user->setUpdateDate($data->userUpdatedAt);
            $user->setAcl($data->userAcl);
        }

        return $data;
    }

    /**
     * Saves a user to the repository.
     *
     * @param User $user The user object to save.
     * @return mixed The result of the insert operation.
     */
    public function save(User $user)
    {
        $data = [
            'userFirstName'  => $user->getFirstName(),
            'userLastName'   => $user->getLastName(),
            'userEmail'      => $user->getEmail(),
            'userPass'       => $user->getPassword(),
            'userCreatedAt'  => date('Y-m-d H:i:s')
        ];

        $data = $this->querybuilder->insert($data);
        return $data;
    }

    /**
     * Updates a user in the repository based on a specific value in a given column.
     *
     * @param User $user The user object with the updated data.
     * @param mixed $value The value to match in the column.
     * @param string $col The column to search for the value.
     * @param string $operator The operator to use in the comparison (default: '=').
     * @return mixed The result of the update operation.
     */
    public function update(User $user, $value, $col = 'userId', $operator = '=')
    {
        $data = [
            'userFirstName'  => $user->getFirstName(),
            'userLastName'   => $user->getLastName(),
            'userEmail'      => $user->getEmail(),
            'userStatus'     => $user->getStatus(),
            'userRole'       => $user->getRole(),
            'userPass'       => $user->getPassword(),
            'userUpdatedAt'  => date('Y-m-d H:i:s')
        ];

        $data = $this->querybuilder->where($col, $operator, $value)
            ->update($data);
        return $data;
    }
}
