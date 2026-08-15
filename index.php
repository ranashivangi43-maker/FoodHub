<?php
session_start();
if(isset($_SESSION['userId'])){
    if($_SESSION['role']=='admin'){
        header("Location: /PHP/Restaurant/admin/dashboard.php");
        exit;
    }
    elseif($_SESSION['role']=='restaurant'){
        header("Location: /PHP/Restaurant/restaurant/dashboard.php");
        exit;
    }
    else{
        header("Location: /PHP/Restaurant/user/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food Ordering Platform</title>
    <!-- font  -->
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- style css -->
    <link rel="stylesheet" href="/PHP/Restaurant/assets/css/style.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">

        <div class="container">

            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
               🍔 <span>FoodHub</span>
            </a>

            <button 
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link custom-link" href="/PHP/Restaurant/index.php">
                            Home
                        </a>
                    </li>

                    

                    <li class="nav-item ms-lg-3">
                        <button 
    class="btn btn-outline-light rounded-pill px-4"
    data-bs-toggle="modal"
    data-bs-target="#loginModal"
>
    Login
</button>
                    </li>

                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <button
    class="btn btn-danger rounded-pill px-4"
    data-bs-toggle="modal"
    data-bs-target="#registerModal"
>
    Register
</button>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
<div id="registerMessage" class="container mt-3"></div>
    <!-- Slider -->
    <div 
        id="foodSlider" 
        class="carousel slide"
        data-bs-ride="carousel"
    >

        <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item active">

                <img 
                src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D";
                    class="d-block w-100"
                    style="height: 600px; object-fit: cover;"
                >

                <div class="carousel-caption d-none d-md-block">

                    <h1 class="display-3 fw-bold">
                        Delicious Foods
                    </h1>

                    <p>
                        Order from your favorite restaurants
                    </p>

                </div>

            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">

                <img 
                    src="https://plus.unsplash.com/premium_photo-1678897750441-b7fe348b14a5?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="d-block w-100"
                    style="height: 600px; object-fit: cover;"
                >

                <div class="carousel-caption d-none d-md-block">

                    <h1 class="fw-bold">
                        Fresh & Tasty
                    </h1>

                    <p>
                        Enjoy premium quality meals
                    </p>

                </div>

            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">

                <img 
                    src="https://images.unsplash.com/photo-1513442542250-854d436a73f2?q=80&w=647&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D "
                    class="d-block w-100"
                    style="height: 600px; object-fit: cover;"
                >

                <div class="carousel-caption d-none d-md-block">

                    <h1 class="fw-bold">
                        Order Now
                    </h1>

                    <p>
                        Hot food delivered quickly
                    </p>

                </div>

            </div>

        </div>

        <!-- Prev Button -->
        <button 
            class="carousel-control-prev"
            type="button"
            data-bs-target="#foodSlider"
            data-bs-slide="prev"
        >

            <span class="carousel-control-prev-icon"></span>

        </button>

        <!-- Next Button -->
        <button 
            class="carousel-control-next"
            type="button"
            data-bs-target="#foodSlider"
            data-bs-slide="next"
        >

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

    <!-- Hero Section -->
    <div class="container py-5 text-center">

        <h1 class="fw-bold mb-3">
            Welcome to FoodHub
        </h1>

        <p class="text-muted fs-5">
            Discover amazing foods from top restaurants near you.
        </p>

        <div class="mt-4">

            

           <!-- Register Button -->
<!-- Register Modal -->
<div 
    class="modal fade"
    id="registerModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content login-clean border-0">

            <div class="p-2 p-md-5">

                <!-- Close -->
                <div class="text-end mb-2">

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <!-- Heading -->
                <div class="text-center mb-4">
                  
        <div class="d-flex justify-content-center align-items-center">
            <i class="bi bi-person-fill-add fs-1"></i>
        </div>
                    <h2 class="fw-bold">
                        Create Account
                    </h2>

                </div>

                <!-- Form -->
                <form 
                    action="process/register_process.php"
                    method="POST"
                    id="registerForm"
                >

                    <!-- Name -->
                    <div class="mb-3">

                        <input
                            type="text"
                            name="name"
                            class="form-control clean-input"
                            placeholder="Full Name"
                        >

                    </div>

                    <div 
                        class="text-danger small mb-2" 
                        id="nameError"
                    ></div>

                    <!-- Email -->
                    <div class="mb-3">

                        <input
                            type="email"
                            name="email"
                            class="form-control clean-input"
                            placeholder="Email address"
                        >

                    </div>

                    <div 
                        class="text-danger small mb-2" 
                        id="emailError"
                    ></div>

                    <!-- Password -->
                    <div class="mb-3">

                        <input
                            type="password"
                            name="password"
                            class="form-control clean-input"
                            placeholder="Password"
                        >

                    </div>

                    <div 
                        class="text-danger small mb-2" 
                        id="passwordError"
                    ></div>

                    <!-- Confirm Password -->
                    <div class="mb-3">

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control clean-input"
                            placeholder="Confirm Password"
                        >

                    </div>

                    <div 
                        class="text-danger small mb-3" 
                        id="confirmError"
                    ></div>

                    <!-- Button -->
                    <button
                        type="submit"
                        class="btn btn-danger w-100 rounded-pill py-3 fw-semibold"
                    >
                        Register
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
<!-- Login Modal -->

<div 
    class="modal fade"
    id="loginModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content login-clean border-0">

            <div class="p-4 p-md-5">

                <!-- Close -->
                <div class="text-end mb-2">

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <!-- Heading -->
                <div class="text-center mb-4">

                    <h2 class="fw-bold">
                        Welcome to FoodHub
                    </h2>

                    

                </div>

                <!-- Form -->
                <form 
                    action="process/login_process.php"
                    method="POST"
                    id="loginForm"
                >

                    <!-- Email -->
                    <div class="mb-3">

                        <input
                            type="text"
                            name="email"
                            class="form-control clean-input"
                            placeholder="Email address"
                            
                        >

                    </div>

                    <div class="text-danger small mb-2" id="loginEmailError"></div>

                    <!-- Password -->
                    <div class="mb-2">

                        <input
                            type="password"
                            name="password"
                            class="form-control clean-input"
                            placeholder="Password"
                            
                        >

                    </div>
                 <div class="text-danger small mb-3" id="loginPasswordError"></div>
                    <!-- Forgot -->
                    <div class="text-center mb-4">

                        <a 
                            href="/PHP/Restaurant/process/forgot_password.php"
                            class="forgot-link"
                        >
                            Forgot Password?
                        </a>

                    </div>

                    

                    <!-- Button -->
                    <button
                        type="submit"
                        class="btn btn-danger w-100 rounded-pill py-3 fw-semibold"
                    >
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>
        </div>
    </div>
</div>
    <!-- Bootstrap JS -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/PHP/Restaurant/assets/js/script.js"></script>
<?php include 'includes/footer.php'; ?> 
</body>
</html>