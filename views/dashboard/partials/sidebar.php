<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-motorcycle"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Shop. Dashboard</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= urlIs('/dashboard') ? "active" : "" ?>">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Products
    </div>

    <!-- Nav Item - Categories -->
    <li class="nav-item <?= urlIs('/tbcategories') ? "active" : "" ?>">
        <a class="nav-link" href="/tbcategories">
            <i class="fas fa-fw fa-list"></i>
            <span>Categories</span>
        </a>
    </li>

    <!-- Nav Item - Products -->
    <li class="nav-item <?= urlIs('/tbproducts') ? "active" : "" ?>">
        <a class="nav-link" href="/tbproducts">
            <i class="fas fa-fw fa-box"></i>
            <span>Products</span>
        </a>
    </li>
    
    <!-- Nav Item - Motorcycles -->
    <li class="nav-item <?= urlIs('/motorcycles/admin') ? "active" : "" ?>">
        <a class="nav-link" href="/motorcycles/admin">
            <i class="fas fa-fw fa-motorcycle"></i>
            <span>Motorcycles</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Operations
    </div>

    <!-- Nav Item - Orders -->
    <li class="nav-item <?= urlIs('/tborders') ? "active" : "" ?>">
        <a class="nav-link" href="/tborders">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </li>

    <!-- Nav Item - Test Rides -->
    <li class="nav-item <?= urlIs('/test-rides/admin') ? "active" : "" ?>">
        <a class="nav-link" href="/test-rides/admin">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Test Rides</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Users
    </div>

    <!-- Nav Item - Users -->
    <li class="nav-item <?= urlIs('/tbusers') ? "active" : "" ?>">
        <a class="nav-link" href="/tbusers">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->