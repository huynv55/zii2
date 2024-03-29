<?php
namespace App\Models\Entities;

class Role extends AbstractEntity
{
    const USER = 'user';
    const ADMIN = 'admin';
    const GUEST = 'guest';
    
    public int $id = 0;
    public ?string $role = null;
    public ?string $description = null;
    public ?bool $active = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct()
    {
        
    }

    public static function fields(): array
    {
        return ['id', 'role', 'description', 'active', 'created_at', 'updated_at'];
    }
}
?>