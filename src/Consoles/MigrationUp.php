<?php
namespace App\Consoles;

class MigrationUp extends ConsoleAbstract
{
    public function run()
    {
        echo 'migration up console';
    }

    public function getCommand(): string
    {
        return 'migration:up';
    }
}
?>