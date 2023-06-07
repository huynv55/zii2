<?php
namespace App\Models;

use App\Models\Entities\User;
use Scrawler\Arca\Model;

class Users extends ArcaModels
{
    public function __construct()
    {
        
    }

    public function initialize()
    {
        parent::initialize();
        $this->table = 'users';
    }

    public function findById(int $id) : User
    {
        $user = $this->getById($id);
        return $this->toUserEntity($user);
    }

    public function findAll() : array
    {
        $results = $this->find()->get();
        $users = [];
        foreach ($results as $key => $result) {
            $users[] = $this->toUserEntity($result);
        }
        return $users;
    }

    public function toUserEntity(Model $model) : User
    {
        $user = new User();
        foreach (User::fields() as $key => $field) {
            $user->{$field} = $model->{$field};
        }
        return $user;
    }
}
?>