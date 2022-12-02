<?php
namespace App\Entities;

class User extends EntityAbstract
{
    public int $id = 0;
    public string $email = '';
    public string $username = '';
    public string $password = '';
    public ?string $reset_hash = null;
    public ?string $reset_at = null;
    public ?string $reset_expires = null;
    public ?string $activate_hash = null;
    public ?string $status = null;
    public ?string $status_message = null;
    public bool $active = true;
    public bool $force_pass_reset = false;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $deleted_at = null;
    public ?string $meta_data = null;

    const PRIMARY_KEY = 'id';

    public array $_accessible = [
        'id',
        'email',
        'username',
        'password',
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
        if(!empty($this->meta_data)) {
            $data['meta_data'] = json_decode($this->meta_data, true);
        }
        return $data;
    }

    public function fromData(array $data)
    {
        if (!empty($data['meta_data']))
        {
            $this->meta_data = json_encode($data['meta_data']);
        }
        if (isset($data['meta_data'])) {
            unset($data['meta_data']);
        }
        parent::fromData($data);
        if(!empty($data[self::PRIMARY_KEY]))
        {
            $this->id = $data[self::PRIMARY_KEY];
        }
        return $this;
    }

    public function setLastLogin()
    {
        $meta_data = [];
        if(!empty($this->meta_data)) {
            $meta_data = json_decode($this->meta_data, true);
        }
        $meta_data['last_login_at'] = date('Y-m-d H:i:s');
        $this->meta_data = json_encode($meta_data);
    }

    public function getLastLogin()
    {
        $meta_data = [];
        if(!empty($this->meta_data)) {
            $meta_data = json_decode($this->meta_data, true);
        }
        return $meta_data['last_login_at'] ?? '';
    }
}
?>