<?php
namespace App\Consoles;

interface ConsoleInterface
{
    /**
     * function run console
     *
     * @return void
     */
    public function run();

    /**
     * return command name
     *
     * @return string
     */
    public function getCommand(): string;

    public function setOptions(array $opts);
}
?>