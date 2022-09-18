<?php
namespace App\Controllers\Home;

use App\Application;
use App\Controllers\AppController;
use App\Requests\Request;
use App\Responses\ResponseHTML;

class IndexAction extends AppController
{
    protected Request $request;
    protected ResponseHTML $response;

    public function __construct(Application $app, Request $request , ResponseHTML $response)
    {
        parent::__construct($app);
        $this->request = $request;
        $this->response = $response;
    }

    public function response()
    {
        return $this->response
                    ->setViewRender('home')
                    ->send();
    }
}

?>