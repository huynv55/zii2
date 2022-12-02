<?php
namespace App\Entities;

class Migration extends EntityAbstract
{
    public int $id;
    public string $name;
    public int $status = 0;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $deleted_at = null;

    const PRIMARY_KEY = 'id';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public array $_accessible = [
        'id',
        'name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function primaryKey()
    {
        return self::PRIMARY_KEY;
    }

    public function fromData(array $data)
    {
        parent::fromData($data);
        if(!empty($data[self::PRIMARY_KEY]))
        {
            $this->id = $data[self::PRIMARY_KEY];
        }
        return $this;
    }
}
?>