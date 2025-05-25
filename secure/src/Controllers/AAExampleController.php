<?php

namespace src\Controllers;

use src\Abstracts\Controller;
use src\Core\PageInfo;

class AAExampleController extends Controller
{
    private PageInfo $pageInfo;

    public function __construct()
    {
        $this->pageInfo = new PageInfo();
    }

    public function index(): int
    {
        $this->pageInfo->title = ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) . " | <page>";
        $data = [];
        $this->render("<page>", $data, $this->pageInfo);
        return 200;
    }
}
