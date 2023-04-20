<?php
namespace App\Controllers\Console;

use ApplicationLoader;

class AppConsole extends AbstractConsole
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        echo 'Console action : app cli';
    }
}
?>