<?php
namespace App\Models;

use App\Models\Entities\EntityInterface;
use Scrawler\Arca\Database;
use Scrawler\Arca\Model;
use Scrawler\Arca\QueryBuilder;
use initializeLoader;

class ArcaModels implements initializeLoader
{
    protected Database $db;
    protected string $table = '';

    public function initialize()
    {
        $this->db = connectionArcaParams();
    }

    public function fromEnity(Model $model, EntityInterface $entity): Model
    {
        $vars = get_object_vars($entity);
        foreach($vars as $property => $value) {
            if($property != 'id') {
                $model->{$property} = $entity->{$property} ?? null;
            } else {
                $model->{'id'} = $entity->{$property} ?? 0;
            }
        }
        return $model;
    }

    public function saveEntity(Model $model, EntityInterface $entity)
    {
        $this->fromEnity($model, $entity)->save();
    }

    public function create() : Model
    {
        return $this->db->create($this->table);
    }

    public function getById(int $id): Model
    {
        return $this->db->get($this->table, $id);
    }

    public function find() : QueryBuilder
    {
        return $this->db->find($this->table);
    }
}