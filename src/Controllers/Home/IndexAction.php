<?php
namespace App\Controllers\Home;

use App\Controllers\AppController;
use App\Requests\Request;
use App\Responses\ResponseHTML;

class IndexAction extends AppController
{
    public function __construct()
    {
        app()->middleware('app');
    }

    public function __invoke(Request $request , ResponseHTML $response)
    {
        throw new \Exception("Error Processing Request", 1);
        
        return $response
                    ->setViewRender('home')
                    ->send();
    }
}

?>