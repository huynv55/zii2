<?php
namespace App\Models;

use App\Entities\EntityInterface;

interface ModelInterface
{
    public function select(array $select);

    public function where(array $where);

    public function order(string $order);

    public function group(string $group);

    public function offset(int $offset);

    public function limit(int $limit);

    public function saveEntity(EntityInterface &$entity);

    public function deleteEntity(EntityInterface &$entity);

    public function fetch() : EntityInterface;

    public function fetchAll() : array;
}
?>