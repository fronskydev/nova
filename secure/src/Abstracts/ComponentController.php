<?php

namespace src\Abstracts;

use src\Core\PageInfo;
use src\Core\PageUtil;

abstract class ComponentController
{
    /**
     * Gets the name of the component.
     *
     * This abstract method should be implemented by subclasses to return the name of the component.
     *
     * @return string The name of the component.
     */
    abstract protected function getComponentName(): string;

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
        $viewFile = SECURE_DIR . "/src/Components/{$this->getComponentName()}/views/{$viewName}.php";
        PageUtil::render($viewFile, $data, $pageInfo);
    }
}
