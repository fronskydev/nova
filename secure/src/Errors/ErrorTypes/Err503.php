<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err503 extends Error
{
    public function __construct()
    {
        parent::__construct(
            503,
            "Service Unavailable",
            ["The server is unavailable due to maintenance or overload."]
        );
    }
}
