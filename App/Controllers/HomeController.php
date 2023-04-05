<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use ApplicationLoader;

class HomeController extends AppController
{

    public function __construct(
        protected UserRepository $repository
    )
    {
        
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $users = $this->repository->getAllUser();
        dd($users);
        $this->render('index');
    }

    public function display()
    {
        $this->render('display');
    }
}
?>