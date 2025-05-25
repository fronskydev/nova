<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err500 extends Error
{
    public function __construct()
    {
        parent::__construct(
            500,
            "Internal Server Error",
            ["An unexpected error occurred on the server."]
        );
    }
}
