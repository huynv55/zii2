<?php
namespace App\Controllers;

use App\Requests\AppRequest;
use ApplicationLoader;

class AppController extends AbstractController
{
    protected AppRequest $request;

    public function initialize()
    {
        parent::initialize();
        $this->request = ApplicationLoader::request(AppRequest::class);
    }

    public function render(string $view, array $data = [])
    {
        $response = ApplicationLoader::response('HtmlResponse');
        /**
         * @var \App\Responses\HtmlResponse $response
         */
        $response
            ->setTemplateView($view)
            ->withData($data)
            ->send();
    }

    public function json(array $data)
    {
        $response = ApplicationLoader::response('JsonResponse');
        /**
         * @var \App\Responses\JsonResponse $response
         */
        $response->withData($data)->send();
    }

    public function redirect(string $url)
    {
        $response = ApplicationLoader::response('RedirectResponse');
        /**
         * @var \App\Responses\RedirectResponse $response
         */
        $response->redirect($url);
    }
}
?>