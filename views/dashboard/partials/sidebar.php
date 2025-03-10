<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/"
            >
            <span class="ms-1 font-weight-bold">Shop. Dashboard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-90" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link  <?= urlIs('/dashboard') ? "active" : "" ?>" href="/dashboard">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-ui-04 text-lg opacity-10 <?= urlIs('/dashboard') ? "" : " text-dark" ?>" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Products pages</h6>
            </li>


            <li class="nav-item">
                <a class="nav-link  <?= urlIs('/tbcategories') ? "active" : "" ?>" href="/tbcategories">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-lg opacity-10 <?= urlIs('/tbcategories') ? "" : " text-dark" ?>" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Category</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= urlIs('/tbproducts') ? "active" : "" ?>" href="/tbproducts">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">

                        <i class="ni ni-app text-lg opacity-10 <?= urlIs('/tbproducts') ? "" : " text-dark" ?>" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Products</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= urlIs('/tbusers') ? "active" : "" ?>" href="/tbusers">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-lg opacity-10 <?= urlIs('/tbusers') ? "" : " text-dark" ?>" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Order pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= urlIs('/tborders') ? "active" : "" ?>" href="/tborders">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-delivery-fast text-lg opacity-10 <?= urlIs('/tborders') ? "" : " text-dark" ?>" aria-hidden="true"></i>

                    </div>
                    <span class="nav-link-text ms-1">Order</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
