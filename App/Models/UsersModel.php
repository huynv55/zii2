<?php
namespace App\Models;

use App\Models\Entities\User;

class UsersModel extends AbstractModel
{
    const TABLE = 'users';
    const TABLE_ROLE = 'roles';
    const TABLE_PERMISSION = 'permissions';
    const TABLE_USER_ROLE = 'user_role';
    const TABLE_ROLE_PERMISSION = 'role_permission';

    public function initialize()
    {
        parent::initialize();
        $this->entityClass = User::class;
    }

    public function findAll(): array
    {
        return 
            $this
                ->setQuery("SELECT * from `".self::TABLE."` WHERE 1")
                ->setParams([])
                ->fetchAll();
    }

    /**
     * get user by id
     *
     * @param integer $id
     * @return User|FALSE
     */
    public function byId(int $id) : User|FALSE
    {
        return 
            $this
                ->setQuery("SELECT * from `".self::TABLE."` WHERE `id` = :id")
                ->setParams(['id' =>  $id])
                ->fetch();
    }

    /**
     * get user active and verified by id
     *
     * @param integer $id
     * @return User|FALSE
     */
    public function activeAndVerifiedById(int $id) : User|FALSE
    {
        return 
            $this
                ->setQuery("SELECT * from `".self::TABLE."` WHERE `id` = :id AND `active` = 1 AND `account_verified_at` IS NOT NULL")
                ->setParams(['id' =>  $id])
                ->fetch();
    }

    /**
     * get user active and verified by account name
     *
     * @param string $account
     * @return User|FALSE
     */
    public function activeAndVerifiedByAccountName(string $account): User|FALSE
    {
        return 
            $this
                ->setQuery("SELECT * from `".self::TABLE."` WHERE `account_name` = :account_name AND `active` = 1 AND `account_verified_at` IS NOT NULL")
                ->setParams(['account_name' =>  $account])
                ->fetch();
    }

    /**
     * check relationship user_id  role_id
     *
     * @param integer $user_id
     * @param integer $role_id
     * @return boolean
     */
    public function userIdbelongRoleId(int $user_id, int $role_id): bool
    {
        $stmp = 
            $this
                ->setQuery("SELECT * from `".self::TABLE_USER_ROLE."` WHERE `user_id` = :user_id AND `role_id` = :role_id")
                ->setParams([
                    'user_id' => $user_id,
                    'role_id' => $role_id
                ])
                ->executeAssoc();
        $result = $stmp->fetch();
        return !empty($result);
    }

}
?>