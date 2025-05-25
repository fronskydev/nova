<?php

namespace src\Abstracts;

use src\Core\PageInfo;
use src\Core\PageUtil;

abstract class Controller
{
    /**
     * Renders a view with the given data and page information.
     *
     * @param string $viewName The name of the view to render.
     * @param array $data The data to be extracted and made available to the view.
     * @param PageInfo $pageInfo The page information object containing metadata and settings for the view.
     * @return void
     */
    protected function render(string $viewName, array $data = [], PageInfo $pageInfo = new PageInfo()): void
    {
        $viewFile = VIEW_DIR . "/$viewName.php";
        PageUtil::render($viewFile, $data, $pageInfo);
    }
}
