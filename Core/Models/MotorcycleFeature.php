<?php

namespace Core\Models;

use Core\App;
use Core\Database;

class MotorcycleFeature
{
    private $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function findByProductId($productId)
    {
        return $this->db->query("
            SELECT * FROM motorcycle_features 
            WHERE product_id = :product_id
            ORDER BY feature_name
        ", ['product_id' => $productId])->get();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_features (product_id, feature_name, feature_description)
            VALUES (:product_id, :feature_name, :feature_description)
        ", [
            'product_id' => $data['product_id'],
            'feature_name' => $data['feature_name'],
            'feature_description' => $data['feature_description'] ?? null
        ]);

        return $this->db->connection->lastInsertId(); 
    }

    public function bulkCreate($productId, $features)
    {
        
        $this->db->connection->beginTransaction(); 

        try {
          
            $this->db->query("DELETE FROM motorcycle_features WHERE product_id = :product_id", 
                ['product_id' => $productId]);
            
            
            foreach ($features as $feature) {
                $this->create([
                    'product_id' => $productId,
                    'feature_name' => $feature['name'],
                    'feature_description' => $feature['description'] ?? null
                ]);
            }
            
           
            $this->db->connection->commit(); 
            return true;
        } catch (\Exception $e) {
            
            $this->db->connection->rollBack(); 
            throw $e;
        }
    }
}