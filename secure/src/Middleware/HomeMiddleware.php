<?php

namespace src\Middleware;

use src\Interfaces\IMiddleware;

class HomeMiddleware implements IMiddleware
{
    public function handle(): int
    {
        echo "<script>alert('HomeMiddleware');</script>";
        return 200;
    }
}
