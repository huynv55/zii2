<?php
namespace App\Models;

use App\Models\Entities\User;

class UsersModel extends AbstractModel
{
    public function initialize()
    {
        parent::initialize();
        $this->entityClass = User::class;
        $this->tableName = 'users';
    }

    public function findById(int $id) : User
    {
        return $this->find()->where(['`id` = :id'])->withParams(['id' => $id])->fetch();
    }

    public function findAll() : array
    {
        return $this->find()->fetchAll();
    }

}
?>