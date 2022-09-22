<?php
/**
 * get instance class application
 *
 * @return App\Application
 */
function app() : App\Application
{
    return $GLOBALS[APPLICATION_NAME];
}

/**
 * get instance container
 *
 * @return DI\Container
 */
function container() : DI\Container
{
    return $GLOBALS[CONTAINER_NAME];
}

?>