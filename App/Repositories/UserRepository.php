<?php
namespace App\Repositories;

use App\Models\UsersModel;
use ApplicationLoader;

class UserRepository extends AbstractRepository
{
    protected UsersModel $userModel;

    public function __construct()
    {
        
    }

    public function initialize()
    {
        parent::initialize();
        $this->userModel = ApplicationLoader::model(UsersModel::class);
    }

    public function getAllUser()
    {
        return $this->userModel->findAll();
    }
}
?>