<?php

namespace src\Abstracts;

abstract class Error
{
    protected int $number;
    protected string $title;
    protected array $description;

    public function __construct(int $number, string $title, array $description)
    {
        $this->number = $number;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Renders the error page with the HTTP status code and title.
     *
     * @return void
     */
    public function render(): void
    {
        $number = $this->number;
        $title = $this->title;
        $description = $this->description;

        header("HTTP/1.1 $number $title");
        require_once VIEW_DIR . "/shared/error.php";
    }
}
