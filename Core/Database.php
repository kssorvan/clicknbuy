<?php
namespace Core;

use PDO;
use Core\Response;

class Database
{
    public $connection; 
    public $statement;

    public function __construct($config, $username = 'root', $password = '')
    {
        // Use the config array keys as provided
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        $this->connection = new PDO($dsn, $config['username'], $config['password'], [ 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query); 
        $this->statement->execute($params);
        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }

    public function findOrFail()
    {
        $result = $this->find();
        if (!$result) {
            abort(Response::NOT_FOUND);
        }
        return $result;
    }
    public function getActiveProducts($query, $params = []) {
        // Append conditions for active products
        $query .= " AND is_deleted = FALSE AND (promotion_expiry IS NULL OR promotion_expiry > NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}