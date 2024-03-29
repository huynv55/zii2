<?php
namespace App\Models\Entities;

interface EntityInterface
{
    public function fromArray(array $data): EntityInterface;

    public function toArray(): array;

    public function validate(): array;

    public static function fields(): array;

    public function primaryKey(): string;
}
?>