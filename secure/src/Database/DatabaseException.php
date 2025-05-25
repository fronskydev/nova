<?php

namespace src\Database;

use Exception;

class DatabaseException extends Exception
{
    public function __construct(string $message, int $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Convert the DatabaseException object to a string representation.
     *
     * @return string A string representation of the DatabaseException object.
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}
