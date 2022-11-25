<?php
namespace App\Migrations;

use App\Entities\Migration;
use App\Models\MigrationsModel;
abstract class AppMigrationAbstract implements MigrationInterface
{
	protected \PDO $db;
	protected MigrationsModel $migrationModel;
	public array $stmps = [];

	public function __construct()
	{
		$this->db = container()->get(\PDO::class);
		$this->migrationModel = container()->get(MigrationsModel::class);
		if(!$this->migrationModel->checkMigrationTableExit())
		{
			$this->migrationModel->createMigrationTable();
		}
	}

	public function getStatus() : int
	{
		return $this->migrationModel->getStatusMigration($this->getName());
	}

	public function addExecuteSql(string $query, array $params = [])
	{
		$this->stmps[] = [
			'query' => $query,
			'params' => $params
		];
	}

	public function executeUpSql()
	{
		if ($this->getStatus() == Migration::STATUS_INACTIVE) {
			foreach ($this->stmps as $key => $query) {
				$stmp = $this->db->prepare($query['query']);
				$stmp->execute($query['params']);
			}
			$this->saveUpMigration();
			echo "Up : ".$this->getName()."\n";
		}
	}

	public function executeDownSql()
	{
		$currentId = $this->migrationModel->getCurrentId();
		$id = $this->migrationModel->getIdByName($this->getName());
		if ($id > 0 && $currentId > 0 && $currentId == $id) {
			foreach ($this->stmps as $key => $query) {
				$stmp = $this->db->prepare($query['query']);
				$stmp->execute($query['params']);
			}
			$this->saveDownMigration();
			echo "Down : ".$this->getName()."\n";
		}
	}

	public function saveUpMigration()
	{
		$this->migrationModel->updateRecordMigration($this->getName(), Migration::STATUS_ACTIVE);
	}

	public function saveDownMigration()
	{
		$this->migrationModel->updateRecordMigration($this->getName(), Migration::STATUS_INACTIVE);
	}
}

?>