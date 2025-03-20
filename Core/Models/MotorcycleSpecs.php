<?php

namespace Core\Models;

use Core\App;
use Core\Database;

class MotorcycleSpecs
{
    private $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function findByProductId($productId)
    {
        return $this->db->query("
            SELECT ms.*, mb.brand_name, mb.logo_url 
            FROM motorcycle_specs ms
            LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
            WHERE ms.product_id = ?
        ", [$productId])->find();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_specs (
                product_id, brand_id, model_year, engine_type, 
                engine_displacement, horsepower, torque, transmission_type,
                gear_count, fuel_capacity, fuel_economy, seat_height,
                weight, vin, mileage, condition, color
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $data['product_id'],
            $data['brand_id'],
            $data['model_year'],
            $data['engine_type'],
            $data['engine_displacement'],
            $data['horsepower'],
            $data['torque'],
            $data['transmission_type'],
            $data['gear_count'],
            $data['fuel_capacity'],
            $data['fuel_economy'],
            $data['seat_height'],
            $data['weight'],
            $data['vin'] ?? null,
            $data['mileage'] ?? 0,
            $data['condition'],
            $data['color']
        ]);

        return $this->db->conncetion->lastInsertId();
    }

    public function update($specId, $data)
    {
        $this->db->query("
            UPDATE motorcycle_specs SET
                brand_id = ?,
                model_year = ?,
                engine_type = ?,
                engine_displacement = ?,
                horsepower = ?,
                torque = ?,
                transmission_type = ?,
                gear_count = ?,
                fuel_capacity = ?,
                fuel_economy = ?,
                seat_height = ?,
                weight = ?,
                vin = ?,
                mileage = ?,
                condition = ?,
                color = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE spec_id = ?
        ", [
            $data['brand_id'],
            $data['model_year'],
            $data['engine_type'],
            $data['engine_displacement'],
            $data['horsepower'],
            $data['torque'],
            $data['transmission_type'],
            $data['gear_count'],
            $data['fuel_capacity'],
            $data['fuel_economy'],
            $data['seat_height'],
            $data['weight'],
            $data['vin'] ?? null,
            $data['mileage'] ?? 0,
            $data['condition'],
            $data['color'],
            $specId
        ]);
    }
}