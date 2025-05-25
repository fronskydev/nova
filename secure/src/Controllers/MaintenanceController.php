<?php

namespace src\Controllers;

class MaintenanceController
{
    public function load(): void
    {
        require_once SECURE_DIR . "/resources/views/shared/maintenance.php";
    }
}
