<?php
namespace App\Repositories;

use App\Models\Users;

class UserRepository extends AbstractRepository
{
    public function __construct(
        protected Users $userModel
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

    public function getUser(int $id)
    {
        return $this->userModel->findById($id);
    }
}
?>