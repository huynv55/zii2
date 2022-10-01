<?php
namespace App\Models;

use App\Entities\User;
use App\Models\Behavior\SoftDelete;

class UsersModel extends AppModel
{
    protected \PDO $db;
    protected string $entityClass = User::class;
    protected string $tableName = 'users';
    protected array $query;

    use SoftDelete;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->query = [];
    }

}

?>