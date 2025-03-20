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
            SELECT * FROM motorcycle_brands WHERE brand_id = ?
        ", [$id])->find();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_brands (brand_name, logo_url, description)
            VALUES (?, ?, ?)
        ", [
            $data['brand_name'],
            $data['logo_url'] ?? null,
            $data['description'] ?? null
        ]);

        return $this->db->conncetion->lastInsertId();
    }

    public function update($id, $data)
    {
        $this->db->query("
            UPDATE motorcycle_brands SET
                brand_name = ?,
                logo_url = ?,
                description = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE brand_id = ?
        ", [
            $data['brand_name'],
            $data['logo_url'] ?? null,
            $data['description'] ?? null,
            $id
        ]);
    }
}