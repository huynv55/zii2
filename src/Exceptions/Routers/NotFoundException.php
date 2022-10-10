<?php
namespace App\Exceptions\Routers;

use App\Exceptions\AppException;

class NotFoundException extends AppException
{
    public function handler()
    {
        parent::handler();
        view()
        ->setViewRender('Exceptions/not_found')
        ->withData(['message' => $this->getMessage()])
        ->send();
    }
}
?>