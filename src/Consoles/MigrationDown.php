<?php
namespace App\Consoles;

class MigrationDown extends ConsoleAbstract
{
    public function run()
    {
        
    }

    public function getCommand(): string
    {
        return 'migration:down';
    }
}
?>