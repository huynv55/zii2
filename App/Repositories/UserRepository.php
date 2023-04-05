<?php
namespace App\Repositories;

use App\Models\UsersModel;

class UserRepository extends AbstractRepository
{
    public function __construct(
        protected UsersModel $userModel
    )
    {
        
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function getAllUser()
    {
        return $this->userModel->findAll();
    }
}
?>