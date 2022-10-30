<?php

declare (strict_types = 1);

namespace App;

require_once __DIR__ . '/Data.php';

class Request extends Data
{
    /**
     * @var mixed
     */
    protected $router;

    /**
     * @param array $items
     */
    public function __construct($router)
    {
        $this->router($router);

        $this->setItems($_REQUEST);

        $this->__set('input', $_POST);

        $this->__set('query', $_GET);
    }

    /**
     * @param mixed $arg
     *
     * @return self
     */
    public function router(Router $arg = null)
    {
        $arg === null ?: $this->router = $arg;

        return $this->router;
    }
}
