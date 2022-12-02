<?php
namespace App\Consoles;

use ClassFinder;

class MigrationUp extends ConsoleAbstract
{
    public array $migrations = [];
    public array $opts = [];

    public function run()
    {
        echo 'migration up console';
        echo "\n";
        echo '---------------------------------------';
        echo "\n";
        $this->init();
        foreach ($this->migrations as $key => $m) {
            $m->up();
            $m->executeUpSql();
        }
        echo "Finished!";
    }

    public function init()
    {
        $migrations = ClassFinder::getInstance()->getListClassInDir(ROOT_PATH.'/migrations');
        foreach ($migrations as $key => $migration) {
            $migrationClass = require realpath(ROOT_PATH.'/migrations/'.$migration.'.php');
            $this->migrations[] = $migrationClass;
        }
    }

    public function getCommand(): string
    {
        return 'migration:up';
    }

    public function setOptions(array $opts)
    {
        $this->opts = $opts;
    }
}
?>