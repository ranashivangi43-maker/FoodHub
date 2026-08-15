<!-- USER NAVBAR -->
<nav class="navbar navbar-expand-lg user-navbar">

    <div class="container">

        <!-- Brand -->
        <a href="dashboard.php" class="navbar-brand user-brand">
            <span class="brand-icon">🍔</span>
            <span>FoodHub</span>
        </a>

        <!-- Mobile Toggle -->
        <button
            class="navbar-toggler user-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#userNavbar"
            aria-controls="userNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="userNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- Home -->
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link user-nav-link">
                        
                        <span>Home</span>
                    </a>
                </li>

                <!-- Orders -->
                <li class="nav-item">
                    <a href="my_orders.php" class="nav-link user-nav-link">
                        
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a href="cart.php" class="nav-link user-icon-link" title="Cart">
                        <i class="bi bi-cart3"></i>

                        <!-- Optional cart count -->
                        <!-- <span class="cart-badge">2</span> -->
                    </a>
                </li>

                <!-- Wishlist -->
                <li class="nav-item">
                    <a href="wishlist.php" class="nav-link user-icon-link" title="Wishlist">
                        <i class="bi bi-suit-heart"></i>
                    </a>
                </li>

                <!-- User -->
                <li class="nav-item dropdown ms-lg-3">

                    <a
                        href="#"
                        class="user-profile dropdown-toggle"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <span class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['userName'], 0, 1)); ?>
                        </span>

                        <span class="user-name">
                            <?php echo htmlspecialchars($_SESSION['userName']); ?>
                        </span>

                    </a>

                    <!-- Profile Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">

                        <li class="dropdown-user-info">

                            <div class="dropdown-avatar">
                                <?php echo strtoupper(substr($_SESSION['userName'], 0, 1)); ?>
                            </div>

                            <div>
                                <strong>
                                    <?php echo htmlspecialchars($_SESSION['userName']); ?>
                                </strong>

                                <small>
                                    FoodHub Customer
                                </small>
                            </div>

                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                href="profile.php"
                                class="dropdown-item user-dropdown-item"
                            >
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>

                        <li>
                            <a
                                href="my_orders.php"
                                class="dropdown-item user-dropdown-item"
                            >
                                <i class="bi bi-bag-check"></i>
                                <span>My Orders</span>
                            </a>
                        </li>

                        <li>
                            <a
                                href="wishlist.php"
                                class="dropdown-item user-dropdown-item"
                            >
                                <i class="bi bi-suit-heart"></i>
                                <span>My Wishlist</span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                href="../restaurant/logout.php"
                                class="dropdown-item user-dropdown-item logout-item"
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