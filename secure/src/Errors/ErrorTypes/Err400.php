<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err400 extends Error
{
    public function __construct()
    {
        parent::__construct(
            400,
            "Bad Request",
            ["The server couldn't understand the request due to syntax or parameters."]
        );
    }
}
