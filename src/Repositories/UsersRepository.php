<?php
namespace App\Repositories;

use App\Entities\User;
use App\Models\UsersModel;

class UsersRepository extends AppRepository
{
    protected UsersModel $model;

    public function __construct(UsersModel $model)
    {
        $this->model = $model;
    }

    public function findById(int $id) : User
    {
        return $this->model->find()
            ->where(['`'.User::PRIMARY_KEY.'` = ?'])
            ->withParams([$id])
            ->fetch();
    }

    public function saveUser(User &$user)
    {
        $this->model->saveEntity($user);
    }
}
?>