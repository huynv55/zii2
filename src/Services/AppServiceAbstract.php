<?php
namespace App\Services;

abstract class AppServiceAbstract implements ServiceInterface
{
    public array $config;
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): ServiceInterface
    {
        $this->config = $config;
        return $this;
    }
}
?>