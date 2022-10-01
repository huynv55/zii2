<?php
namespace App\Entities;

class User extends Entity
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

    public function primaryKey()
    {
        return 'id';    
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'status' => $this->status,
            'status_message' => $this->status_message,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'meta_data' => json_decode($this->meta_data, true)
        ];
    }
}
?>