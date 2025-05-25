<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err404 extends Error
{
    public function __construct()
    {
        parent::__construct(
            404,
            "Page Not Found",
            ["The page you were looking for does not exist."]
        );
    }
}
