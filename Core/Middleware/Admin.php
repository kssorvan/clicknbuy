namespace Core\Middleware;

class Admin
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'superuser'])) {
            header('Location: /login');
            exit();
        }
    }
}