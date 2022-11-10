<?php
namespace App\Models;

use App\Entities\User;
use App\Models\Behavior\SoftDelete;
use App\Models\Behavior\ValidateEntity;

class UsersModel extends AppModelAbstract
{
    protected \PDO $db;
    protected string $entityClass = User::class;
    protected string $tableName = 'users';
    protected array $query;

    /**
     * array list messages validation
     * more detail https://github.com/rakit/validation
     * @var array
     */
    public array $messages = [];

    /**
     * array validate config
     * more detail https://github.com/rakit/validation
     * @var array
     */
    public array $validate = [];

    use SoftDelete;
    use ValidateEntity;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->query = [];
    }

}

?>