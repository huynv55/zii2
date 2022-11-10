<?php
namespace App\Migrations;

interface MigrationInterface
{
	/**
	 * migrate up function
	 */
	public function up();

	/**
	 * migrate down function
	 */
	public function down();

	/**
	 * get name
	 * @return string
	 */
	public function getName(): string;

	/**
	 * get status
	 * @return int
	 */
	public function getStatus(): int;
}
?>