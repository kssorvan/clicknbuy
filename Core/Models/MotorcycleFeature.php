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
            WHERE product_id = ?
            ORDER BY feature_name
        ", [$productId])->get();
    }

    public function create($data)
    {
        $this->db->query("
            INSERT INTO motorcycle_features (product_id, feature_name, feature_description)
            VALUES (?, ?, ?)
        ", [
            $data['product_id'],
            $data['feature_name'],
            $data['feature_description'] ?? null
        ]);

        return $this->db->conncetion->lastInsertId();
    }

    public function bulkCreate($productId, $features)
    {
        // Start transaction
        $this->db->conncetion->beginTransaction();

        try {
            // Delete existing features
            $this->db->query("DELETE FROM motorcycle_features WHERE product_id = ?", [$productId]);
            
            // Insert new features
            foreach ($features as $feature) {
                $this->create([
                    'product_id' => $productId,
                    'feature_name' => $feature['name'],
                    'feature_description' => $feature['description'] ?? null
                ]);
            }
            
            // Commit transaction
            $this->db->conncetion->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback on error
            $this->db->conncetion->rollBack();
            throw $e;
        }
    }
}