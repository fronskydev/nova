<?php

namespace src\Core;

class PageInfo
{
    public string $title = "";
    public array $styles = [];
    public array $scripts = [];
    public bool $bootstrapEnabled = true;
    public bool $headerEnabled = true;
    public bool $footerEnabled = true;
    public bool $cookiesCheckEnabled = true;
    public bool $container = true;
}
