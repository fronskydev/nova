<?php

namespace src\Interfaces;

interface IMiddleware
{
    /**
     * Handles the middleware logic.
     *
     * @return int The HTTP status code to return.
     */
    public function handle(): int;
}
