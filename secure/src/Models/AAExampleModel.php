<?php

namespace src\Models;

use src\Abstracts\Model;

class AAExampleModel extends Model
{
    protected function getTable(): string
    {
        return "<table_name>";
    }
}
