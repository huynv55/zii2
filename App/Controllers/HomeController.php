<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Responses\JsonResponse;

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

    public function display(JsonResponse $response)
    {
        return $response->withData(['controller' => 'home', 'action' => 'display', 'message' => 'ok'])->send();
    }
}
?>