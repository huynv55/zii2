<?php
namespace App\Models;

use App\Entities\Migration;
use PDO;

class MigrationsModel extends AppModelAbstract
{
    protected \PDO $db;
    protected string $entityClass = Migration::class;
    protected string $tableName = 'migrations';
    protected array $query;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->query = [];
    }

    public function checkMigrationTableExit()
    {
        if (empty($GLOBALS['checkMigrationTableExit'])) {
            $q = "SELECT COUNT(*) as `count_results` FROM `migrations` WHERE 1";
            $stmp = $this->db->prepare($q);
            $stmp->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmp->fetch();
            $GLOBALS['checkMigrationTableExit'] = $result['count_results'] ?? 0;
            return $GLOBALS['checkMigrationTableExit'];
        } else {
            return $GLOBALS['checkMigrationTableExit'];
        }
    }

    public function createMigrationTable()
    {
        $q = "CREATE TABLE IF NOT EXISTS `migrations`  (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `status` int NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;";
        $stmp = $this->db->prepare($q);
        return $stmp->execute();
    }

    public function insertRecordMigration(string $name)
    {
        $m = $this->find()->where(["`name` = :name"])->withParams(['name' => $name])->fetch();
        if (empty($m))
        {
            $migration = new Migration();
            $migration->id = 0;
            $migration->name = $name;
            $migration->status = Migration::STATUS_ACTIVE;
            $this->saveEntity($migration);
        }
    }

    public function updateRecordMigration(string $name, int $status)
    {
        $migration = $this->find()->where(["`name` = :name"])->withParams(['name' => $name])->fetch();
        if (!empty($migration))
        {
            $migration->name = $name;
            $migration->status = $status;
            $this->saveEntity($migration);
        } else {
            $migration = new Migration();
            $migration->id = 0;
            $migration->name = $name;
            $migration->status = Migration::STATUS_ACTIVE;
            $this->saveEntity($migration);
        }
    }

    public function getStatusMigration(string $name)
    {
        $migration = $this->find()->where(["`name` = :name"])->withParams(['name' => $name])->fetch();
        if (!empty($migration)) {
            return $migration->status;
        } else {
            return Migration::STATUS_INACTIVE;
        }
    }

    public function getListMigration()
    {
        return $this->find()->fetchAll();
    }

    public function getCurrentId()
    {
        $migration = $this->find()->where(["`status` = :status"])->order('`id` DESC')->withParams(['status' => Migration::STATUS_ACTIVE])->fetch();
        if (empty($migration)) {
            return 0;
        } else {
            return $migration->id;
        }
    }

    public function getIdByName($name)
    {
        $migration = $this->find()->where(["`name` = :name"])->withParams(['name' => $name])->fetch();
        if (!empty($migration)) {
            return $migration->id;
        } else {
            return 0;
        }
    }

}

?>