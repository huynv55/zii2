<?php
namespace App\Responses;

use App\Resources\ResourceInterface;
use App\Views\AppHelper;
use App\Views\DateHelper;
use League\Plates\Engine as PhpRenderer;
use League\Plates\Extension\URI as URIHepler;
use League\Plates\Extension\Asset as AssetHepler;
class ResponseHTML extends Response
{
    protected PhpRenderer $template;
    protected string $view;
    protected array $data;
    const TEMPLATE_PATH = __DIR__."/../Templates/Web";
    const ASSET_PATH = __DIR__."/../../public";

    public function __construct()
    {
        parent::__construct();
        $this->initTemplate();
        $this->headers['content-type'] = 'text/html;charset=UTF-8';
        $this->data = [];
        $this->data['settings'] = app()->getConfig();
        $this->view = '';
    }

    public function initTemplate()
    {
        $this->template = new PhpRenderer(self::TEMPLATE_PATH);
        $this->template->loadExtension(new URIHepler($_SERVER['REQUEST_URI']));
        $this->template->loadExtension(new AssetHepler(self::ASSET_PATH));
        $this->template->loadExtension(new AppHelper());
        $this->template->loadExtension(new DateHelper());
    }

    public function send()
    {
        $resources = [];
        if(!is_null($this->body)) {
            $resources = $this->body->toArray();
        }
        echo $this->template->render($this->view, array_merge($resources, $this->data));
        parent::send();
    }

    public function withData(array $data) : ResponseHTML
    {
        $this->data = $data;
        return $this;
    }

    public function setViewRender(string $view) : ResponseHTML
    {
        $this->view = $view;
        return $this;
    }
}

?>