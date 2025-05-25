<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err403 extends Error
{
    public function __construct()
    {
        parent::__construct(
            403,
            "Forbidden",
            ["The server understood the request but refuses due to permission denial."]
        );
    }
}
