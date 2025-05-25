<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err000 extends Error
{
    public function __construct()
    {
        parent::__construct(
            000,
            "Error Not Found",
            ["The requested error code has not been found."]
        );
    }
}
