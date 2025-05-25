<?php

namespace src\Middleware;

use src\Interfaces\IMiddleware;

class AAExampleMiddleware implements IMiddleware
{
    public function handle(): int
    {
        return 200;
    }
}
