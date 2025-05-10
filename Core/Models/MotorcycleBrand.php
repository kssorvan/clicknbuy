<?php

namespace Core\Models;

use Core\App;
use Core\Database;

class MotorcycleBrand
{
    private $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function all()
    {
        return $this->db->query("
            SELECT * FROM motorcycle_brands ORDER BY brand_name
        ")->get();
    }

    public function find($id)
    {
        return $this->db->query("
            SELECT * FROM motorcycle_brands WHERE brand_id = :brand_id
        ", ['brand_id' => $id])->find();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_brands (brand_name, logo_url, description)
            VALUES (:brand_name, :logo_url, :description)
        ", [
            'brand_name' => $data['brand_name'],
            'logo_url' => $data['logo_url'] ?? null,
            'description' => $data['description'] ?? null
        ]);

        return $this->db->connection->lastInsertId(); // Fixed typo: conncetion → connection
    }

    public function update($id, $data)
    {
        $this->db->query("
            UPDATE motorcycle_brands SET
                brand_name = :brand_name,
                logo_url = :logo_url,
                description = :description,
                updated_at = CURRENT_TIMESTAMP
            WHERE brand_id = :brand_id
        ", [
            'brand_name' => $data['brand_name'],
            'logo_url' => $data['logo_url'] ?? null,
            'description' => $data['description'] ?? null,
            'brand_id' => $id
        ]);
    }
}