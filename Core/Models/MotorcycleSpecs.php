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
            WHERE ms.product_id = :product_id
        ", ['product_id' => $productId])->find();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_specs (
                product_id, brand_id, model_year, engine_type, 
                engine_displacement, horsepower, torque, transmission_type,
                gear_count, fuel_capacity, fuel_economy, seat_height,
                weight, vin, mileage, condition, color
            ) VALUES (
                :product_id, :brand_id, :model_year, :engine_type, 
                :engine_displacement, :horsepower, :torque, :transmission_type,
                :gear_count, :fuel_capacity, :fuel_economy, :seat_height,
                :weight, :vin, :mileage, :condition, :color
            )
        ", [
            'product_id' => $data['product_id'],
            'brand_id' => $data['brand_id'],
            'model_year' => $data['model_year'],
            'engine_type' => $data['engine_type'],
            'engine_displacement' => $data['engine_displacement'],
            'horsepower' => $data['horsepower'],
            'torque' => $data['torque'],
            'transmission_type' => $data['transmission_type'],
            'gear_count' => $data['gear_count'],
            'fuel_capacity' => $data['fuel_capacity'],
            'fuel_economy' => $data['fuel_economy'],
            'seat_height' => $data['seat_height'],
            'weight' => $data['weight'],
            'vin' => $data['vin'] ?? null,
            'mileage' => $data['mileage'] ?? 0,
            'condition' => $data['condition'],
            'color' => $data['color']
        ]);

        return $this->db->connection->lastInsertId(); // Fixed typo: conncetion → connection
    }

    public function update($specId, $data)
    {
        $this->db->query("
            UPDATE motorcycle_specs SET
                brand_id = :brand_id,
                model_year = :model_year,
                engine_type = :engine_type,
                engine_displacement = :engine_displacement,
                horsepower = :horsepower,
                torque = :torque,
                transmission_type = :transmission_type,
                gear_count = :gear_count,
                fuel_capacity = :fuel_capacity,
                fuel_economy = :fuel_economy,
                seat_height = :seat_height,
                weight = :weight,
                vin = :vin,
                mileage = :mileage,
                condition = :condition,
                color = :color,
                updated_at = CURRENT_TIMESTAMP
            WHERE spec_id = :spec_id
        ", [
            'brand_id' => $data['brand_id'],
            'model_year' => $data['model_year'],
            'engine_type' => $data['engine_type'],
            'engine_displacement' => $data['engine_displacement'],
            'horsepower' => $data['horsepower'],
            'torque' => $data['torque'],
            'transmission_type' => $data['transmission_type'],
            'gear_count' => $data['gear_count'],
            'fuel_capacity' => $data['fuel_capacity'],
            'fuel_economy' => $data['fuel_economy'],
            'seat_height' => $data['seat_height'],
            'weight' => $data['weight'],
            'vin' => $data['vin'] ?? null,
            'mileage' => $data['mileage'] ?? 0,
            'condition' => $data['condition'],
            'color' => $data['color'],
            'spec_id' => $specId
        ]);
    }
}