<?php
namespace App\Controllers;

use App\Repositories\UserRepository;

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
        $this->render('index');
    }

    public function display()
    {
        $this->render('display');
    }
}
?>