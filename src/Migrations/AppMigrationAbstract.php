<?php
namespace App\Migrations;

abstract class AppMigrationAbstract implements MigrationInterface
{

	public function getName() : string
	{
		return static::class;
	}

	public function getStatus() : int
	{
		return 0;
	}
}

?>