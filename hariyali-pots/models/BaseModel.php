<?php
require_once __DIR__ . '/../config/db.php';

class BaseModel
{
    protected PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }
}
