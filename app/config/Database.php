<?php

class Database
{
    private string $host = 'localhost';
    private string $dbname = 'thailand_db';
    private string $username = 'root';
    private string $password = '';
    private PDO $pdo;
    private array $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $this->pdo = new PDO($dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            echo "การเชื่อมต่อล้มเหลว: " . $e->getMessage();
            exit;
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
