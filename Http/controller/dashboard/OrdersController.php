<?php
namespace App\Controllers\Dashboard;

use Core\App;

class OrdersController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::get('database');
    }

    public function index()
    {
        $orders = $this->db->query("
            SELECT o.*, u.name AS user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.user_id 
            WHERE o.is_deleted = FALSE
        ")->fetchAll();

        require base_path('app/views/dashboard/orders/index.php');
    }

    public function update()
    {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET status = ?, payment_status = ?, updated_at = NOW()
            WHERE order_id = ?
        ");
        $stmt->execute([$_POST['status'], $_POST['payment_status'], $_POST['order_id']]);

        header('Location: /tborders');
        exit;
    }

    public function delete()
    {
        $stmt = $this->db->prepare("UPDATE orders SET is_deleted = TRUE WHERE order_id = ?");
        $stmt->execute([$_POST['order_id']]);

        header('Location: /tborders');
        exit;
    }

    public function markPaid()
    {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET payment_status = 'paid', updated_at = NOW() 
            WHERE order_id = ?
        ");
        $stmt->execute([$_POST['order_id']]);

        header('Location: /tborders');
        exit;
    }
}