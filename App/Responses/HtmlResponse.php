<?php
namespace App\Responses;

use League\Plates\Engine as PhpRenderer;

class HtmlResponse extends AbstractResponse
{
    protected string $view = 'index';
    protected array $data = [];

    public function __construct(
        protected PhpRenderer $template
    )
    {
        
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function withData(array $data) : HtmlResponse
    {
        $this->data = $data;
        return $this;
    }

    public function setTemplateView(string $view) : HtmlResponse
    {
        $this->view = $view;
        return $this;
    }

    public function send()
    {
        $resources = [];
        // todo global data into view
        $this->setContent($this->template->render($this->view, array_merge($resources, $this->data)));
        parent::send();
    }
}

?>