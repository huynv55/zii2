<?php
namespace App\Controllers;
use App\Application;

class AppController extends Controller
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }


}
?>