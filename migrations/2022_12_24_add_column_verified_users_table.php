<?php
use App\Migrations\AppMigrationAbstract;

return new class extends AppMigrationAbstract
{
    public function up()
    {
        $this->addExecuteSql('ALTER TABLE `users` ADD COLUMN `verified` tinyint NULL AFTER `meta_data`');
    }

    public function down()
    {
        $this->addExecuteSql('ALTER TABLE `users` DROP COLUMN `verified`');
    }

    public function getName(): string
    {
        return pathinfo(__FILE__, PATHINFO_FILENAME);
    }
}
?>