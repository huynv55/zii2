<?php
namespace App\Models\Entities;

class Permission extends AbstractEntity
{
    const FULL_ACCESS = 'full';
    const CAN_VIEW = 'view';
    const CAN_CREATE = 'create';
    const CAN_UPDATE = 'update';
    const CAN_DELETE = 'delete';
    
    public int $id = 0;
    public ?string $permission = null;
    public ?string $description = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
}
?>