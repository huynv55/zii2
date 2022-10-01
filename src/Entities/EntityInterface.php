<?php
namespace App\Entities;

interface EntityInterface
{
    public function primaryKey();

    public function toArray() : array;
}
?>