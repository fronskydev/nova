<?php

namespace src\Errors\ErrorTypes;

use src\Abstracts\Error;

class Err502 extends Error
{
    public function __construct()
    {
        parent::__construct(
            502,
            "Bad Gateway",
            ["The server received an invalid response from upstream."]
        );
    }
}
