<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use ApplicationLoader;

class HomeController extends AppController
{
    protected UserRepository $repository;

    public function initialize()
    {
        parent::initialize();
        $this->repository = ApplicationLoader::repository(UserRepository::class);
    }

    public function index()
    {
        $this->render('index');
    }

    public function display()
    {
        $this->render('display');
    }
}
?>