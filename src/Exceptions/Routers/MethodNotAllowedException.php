<?php
namespace App\Exceptions\Routers;

use App\Exceptions\AppException;

class MethodNotAllowedException extends AppException
{
    public function handler()
    {
        parent::handler();
        view()
        ->setViewRender('Exceptions/method_not_allow')
        ->withData(['message' => $this->getMessage()])
        ->send();
    }
}
?>