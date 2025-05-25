<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err401 extends Error
{
    public function __construct()
    {
        parent::__construct(
            401,
            "Unauthorized",
            ["The request requires user authentication."]
        );
    }
}
