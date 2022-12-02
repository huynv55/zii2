<?php
namespace App\Consoles;

use ClassFinder;

class MigrationDown extends ConsoleAbstract
{
    public array $migrations = [];
    public array $opts = [];

    public function run()
    {
        echo 'migration down console';
        echo "\n";
        echo '---------------------------------------';
        echo "\n";
        $this->init();
        foreach ($this->migrations as $key => $m) {
            $m->down();
            $m->executeDownSql();
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
        return 'migration:down';
    }

    public function setOptions(array $opts)
    {
        $this->opts = $opts;
    }
}
?>