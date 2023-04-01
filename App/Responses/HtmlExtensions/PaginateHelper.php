<?php

namespace App\Responses\HtmlExtensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Extension that render html paginate.
 */
class PaginateHelper implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * Create new paginate instance.
     */
    public function __construct()
    {

    }

    /**
     * Register extension function.
     * @param Engine $engine
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('paginate', [$this, 'getPaginateInstance']);
    }

    public function getPaginateInstance()
    {
        return $this;
    }

}
