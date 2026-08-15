<?php
$userName = $_SESSION['userName'] ?? 'Restaurant Owner';
?>

<nav class="navbar navbar-expand-lg restaurant-navbar">

    <div class="container">

        <!-- Brand -->
        <a href="dashboard.php"
           class="navbar-brand restaurant-brand">

            <span class="brand-icon">🍔</span>
            <span>FoodHub</span>

        </a>


        <!-- Mobile Toggle -->
        <button
            class="navbar-toggler restaurant-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#restaurantNavbar"
            aria-controls="restaurantNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- Navigation -->
        <div
            class="collapse navbar-collapse"
            id="restaurantNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- Dashboard -->
                <li class="nav-item">

                    <a
                        href="dashboard.php"
                        class="nav-link restaurant-nav-link"
                    >
                        Dashboard
                    </a>

                </li>


                <!-- Add Food -->
                <li class="nav-item">

                    <a
                        href="add_food.php"
                        class="nav-link restaurant-nav-link"
                    >
                        Add Food
                    </a>

                </li>


                <!-- My Foods -->
                <li class="nav-item">

                    <a
                        href="manage_food.php"
                        class="nav-link restaurant-nav-link"
                    >
                        My Foods
                    </a>

                </li>

<li class="nav-item">
    <a href="orders.php" class="nav-link restaurant-nav-link">
        Orders
    </a>
</li>
                <!-- Pending -->
                <li class="nav-item">

                    <a
                        href="pending_foods.php"
                        class="nav-link restaurant-nav-link"
                    >
                        Pending
                    </a>

                </li>


                <!-- User Dropdown -->
                <li class="nav-item dropdown ms-lg-3">

                    <a
                        href="#"
                        class="restaurant-user dropdown-toggle"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <span class="restaurant-avatar">
                            <?php echo strtoupper(substr($userName, 0, 1)); ?>
                        </span>

                        <span class="restaurant-user-name">
                            <?php echo htmlspecialchars($userName); ?>
                        </span>

                    </a>


                    <!-- Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end restaurant-dropdown">

                        <li class="dropdown-user-info">

                            <div class="dropdown-avatar">
                                <?php echo strtoupper(substr($userName, 0, 1)); ?>
                            </div>

                            <div>
                                <strong>
                                    <?php echo htmlspecialchars($userName); ?>
                                </strong>

                                <small>
                                    Restaurant Owner
                                </small>
                            </div>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <!-- Edit Profile -->
                        <li>

                            <a
                                href="edit_profile.php"
                                class="dropdown-item restaurant-dropdown-item"
                            >
                                <i class="bi bi-person-gear"></i>
                                <span>Edit Profile</span>
                            </a>

                        </li>


                        <!-- Logout -->
                        <li>

                            <a
                                href="logout.php"
                                class="dropdown-item restaurant-dropdown-item logout-item"
                            >
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>