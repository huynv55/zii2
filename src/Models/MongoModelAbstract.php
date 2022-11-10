<?php
namespace App\Models;

abstract class MongoModelAbstract
{
    protected \MongoDB\Client $mongo;
    protected string $entityClass;
    protected \MongoDB\Collection $collection;
    protected array $query;
    protected array $columns = [];
    protected string $dbName = 'coupons';
    protected string $collectionName;

    public function initialize() : \MongoDB\Collection
    {
        $this->query = [];
        $otps = [
            'typeMap' => 
                [
                    'root' => $this->entityClass,
                    //'document' => 'array',
                    //'array' => 'array'
                ]
            ];
        $this->collection = $this->mongo->selectCollection($this->dbName, $this->collectionName, $otps);
        return $this->collection;
    }
}
?>