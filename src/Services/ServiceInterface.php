<?php
namespace App\Services;

interface ServiceInterface
{
    public function getConfig() : array;
    public function setConfig(array $config) : self;
}
?>