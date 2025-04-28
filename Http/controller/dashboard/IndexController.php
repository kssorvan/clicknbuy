<?php
namespace Http\Controllers\Dashboard;

use Core\App;
use Cloudinary\Cloudinary;
class IndexController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function index()
    {
        // Monthly Earnings
        $monthlyEarnings = $this->db->query("
            SELECT SUM(total_amount) as total
            FROM orders
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'canceled'
        ")->find()['total'] ?? 0;

        // Annual Earnings
        $annualEarnings = $this->db->query("
            SELECT SUM(total_amount) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'canceled'
        ")->find()['total'] ?? 0;

        // Test Ride Completion
        $totalTestRides = $this->db->query("
            SELECT COUNT(*) as total
            FROM test_rides
            WHERE MONTH(requested_date) = MONTH(CURRENT_DATE())
            AND YEAR(requested_date) = YEAR(CURRENT_DATE())
        ")->find()['total'] ?? 0;

        $completedTestRides = $this->db->query("
            SELECT COUNT(*) as total
            FROM test_rides
            WHERE MONTH(requested_date) = MONTH(CURRENT_DATE())
            AND YEAR(requested_date) = YEAR(CURRENT_DATE())
            AND status = 'completed'
        ")->find()['total'] ?? 0;

        $taskProgress = $totalTestRides > 0 ? round(($completedTestRides / $totalTestRides) * 100) : 0;
        //totalmoto
        $totalMotorcycles = $this->db->query("SELECT COUNT(*) as total FROM products WHERE is_deleted = FALSE")->find()['total'] ?? 0;
        $featuredMotorcycles = $this->db->query("SELECT COUNT(*) as total FROM products WHERE is_deleted = FALSE AND is_featured = TRUE")->find()['total'] ?? 0;
        
        // Pending Test Rides
        $pendingRequests = $this->db->query("
            SELECT COUNT(*) as total
            FROM test_rides
            WHERE status = 'pending'
        ")->find()['total'] ?? 0;

        // Recent Orders
        $recentOrders = $this->db->query("
            SELECT o.order_id, u.name as customer, o.total_amount, o.status
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE o.is_deleted = FALSE
            ORDER BY o.created_at DESC
            LIMIT 5
        ")->get();

        // Recent Test Rides
        $recentTestRides = $this->db->query("
            SELECT u.name as customer, p.name as model, t.requested_date, t.requested_time, t.status
            FROM test_rides t
            JOIN users u ON t.user_id = u.user_id
            JOIN products p ON t.product_id = p.product_id
            WHERE t.is_deleted = FALSE
            ORDER BY t.requested_date DESC
            LIMIT 5
        ")->get();

        // Earnings Chart Data
        $earningsData = $this->db->query("
            SELECT MONTHNAME(created_at) as month, SUM(total_amount) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'canceled'
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at)
        ")->get();

        $earningsLabels = json_encode(array_column($earningsData, 'month'));
        $earningsData = json_encode(array_column($earningsData, 'total'));

        // Revenue Sources Data
        $revenueData = $this->db->query("
            SELECT payment_method, SUM(total_amount) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'canceled'
            GROUP BY payment_method
        ")->get();

        $revenueLabels = json_encode(array_column($revenueData, 'payment_method'));
        $revenueData = json_encode(array_column($revenueData, 'total'));

        view('dashboard/index.php', [
            'monthlyEarnings' => $monthlyEarnings,
            'annualEarnings' => $annualEarnings,
            'taskProgress' => $taskProgress,
            'pendingRequests' => $pendingRequests,
            'recentOrders' => $recentOrders,
            'recentTestRides' => $recentTestRides,
            'earningsLabels' => $earningsLabels,
            'earningsData' => $earningsData,
            'revenueLabels' => $revenueLabels,
            'revenueData' => $revenueData,
            'totalMotorcycles' => $totalMotorcycles,
            'featuredMotorcycles' => $featuredMotorcycles
        ]);
    }


    public function exportEarnings(){
        $earnings = $this->db->query("
            SELECT MONTHNAME(created_at) as month, SUM(total_amount) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
            AND status != 'canceled'
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at)
        ")->get();
    
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=earnings.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Month', 'Total Earnings']);
        foreach ($earnings as $row) {
            fputcsv($output, [$row['month'], $row['total']]);
        }
        fclose($output);
        exit;
    }

    
    public function pendingTestRides(){
    $pendingRequests = $this->db->query("SELECT COUNT(*) as total FROM test_rides WHERE status = 'pending'")->find()['total'] ?? 0;
    response()->json(['pendingRequests' => $pendingRequests]);
    }
    
}