// Create this file at: views/errors/404.view.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="display-1">404</h1>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="lead mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>