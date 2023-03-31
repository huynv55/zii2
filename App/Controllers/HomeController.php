<?php
namespace App\Controllers;

class HomeController extends AppController
{

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