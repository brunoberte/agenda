<?php

namespace Agenda\Models;

use PDO;

abstract class AppModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new PDO(getenv('DATABASE_URI'));
    }
}
