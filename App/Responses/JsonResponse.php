<?php
namespace App\Responses;

class JsonResponse extends AbstractResponse
{
    protected array $data = [];

    public function initialize()
    {
        parent::initialize();
        $this->headers = [
            'Content-type' => 'application/json'
        ];
    }

    public function withData(array $data) : JsonResponse
    {
        $this->data = $data;
        return $this;
    }

    public function send()
    {
        $resources = [];
        $this->setContent(json_encode(array_merge($resources, $this->data)));
        parent::send();
    }
}
?>