<?php
namespace App\Models\Entities;


class User extends AbstractEntity
{
    public int $id = 0;
    public ?string $account_name = null;
    public ?string $email = null;
    public ?string $full_name = null;
    public ?string $avatar = null;
    public ?string $address = null;
    public ?string $region = null;
    public ?bool $active = null;
    public ?string $account_verified_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct()
    {
        
    }
}
?>