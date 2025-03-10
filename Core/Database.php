<?php

namespace Core;

use PDO;
use Core\Response;

class Database
{
    public $conncetion;
    public $statement;
    public function __construct($config, $username = 'root', $password = '')
    {
        $dns = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        $this->conncetion = new PDO($dns, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    public function query($query, $params = [])
    {

        $this->statement = $this->conncetion->prepare($query);
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
}
