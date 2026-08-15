<nav class="navbar navbar-expand-lg fixed-top admin-navbar">

    <div class="container">

        <a
            class="navbar-brand fw-bold d-flex align-items-center gap-2"
            href="/PHP/Restaurant/admin/dashboard.php"
        >
            🍔 <span>FoodHub</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#adminNavbar"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="adminNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a
                        href="/PHP/Restaurant/admin/dashboard.php"
                        class="nav-link admin-nav-link"
                    >
                        Dashboard
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a
                        href="/PHP/Restaurant/admin/manage_restaurant.php"
                        class="nav-link admin-nav-link"
                    >
                        Restaurants
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a
                        href="/PHP/Restaurant/admin/approve_food.php"
                        class="nav-link admin-nav-link"
                    >
                        Food Approvals
                    </a>
                </li>

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a
                        href="../restaurant/logout.php"
                        class="btn btn-danger rounded-pill px-4"
                    >
                        Logout
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>