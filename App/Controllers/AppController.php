<?php
namespace App\Controllers;

use App\Requests\AppRequest;
use App\Responses\HtmlResponse;
use App\Responses\JsonResponse;
use ApplicationLoader;

class AppController extends AbstractController
{
    protected HtmlResponse $response;
    protected AppRequest $request;
    protected JsonResponse $jsonResponse;

    public function initialize()
    {
        parent::initialize();
        $this->response = ApplicationLoader::response(HtmlResponse::class);
        $this->jsonResponse = ApplicationLoader::response(JsonResponse::class);
        $this->request = ApplicationLoader::request(AppRequest::class);
    }

    public function render(string $view, array $data = [])
    {
        $this
            ->response
            ->setTemplateView($view)
            ->withData($data)
            ->send();
    }

    public function json(array $data)
    {
        $this->jsonResponse->withData($data)->send();
    }
}
?>