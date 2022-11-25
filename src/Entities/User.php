<?php
namespace App\Entities;

class User extends EntityAbstract
{
    public int $id;
    public string $email;
    public ?string $username;
    public string $password_hash;
    public ?string $reset_hash;
    public ?string $reset_at;
    public ?string $reset_expires;
    public ?string $activate_hash;
    public ?string $status;
    public ?string $status_message;
    public bool $active;
    public bool $force_pass_reset;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $deleted_at;
    public ?string $meta_data;

    const PRIMARY_KEY = 'id';

    public array $_accessible = [
        'id',
        'email',
        'status',
        'status_message',
        'active',
        'created_at',
        'updated_at',
        'meta_data'
    ];

    public function primaryKey()
    {
        return self::PRIMARY_KEY;    
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['meta_data'] = json_decode($this->meta_data, true);
        return $data;
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