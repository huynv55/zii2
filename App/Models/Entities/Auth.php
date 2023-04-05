<?php
namespace App\Models\Entities;

class Auth extends AbstractEntity
{
    public int $id = 0;
    public ?int $user_id = null;
    public ?string $password = null;
    public ?string $token = null;
    public ?bool $active = null;
    public ?string $last_login_at = null;
    public ?string $created_at = null;

    public function __construct()
    {
        
    }
}
?>