<?php
include '../config/db.php';

$token = $_GET['token'] ?? '';
$err = '';

$hashed_token = hash('sha256', $token);

$get = $conn->prepare("
    SELECT * FROM password_resets 
    WHERE token=?
");

$get->execute([$hashed_token]);

$data = $get->fetch(PDO::FETCH_ASSOC);

if(!$data){
    die("Invalid or expired token");
}

if(strtotime($data['expires_at']) < time()){
    die("Token expired");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if(empty($password) || empty($confirm)){

        $err = "All fields are required";

    }
    
    elseif($password != $confirm){

        $err = "Passwords do not match";

    }
    else{

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users 
            SET password=? 
            WHERE email=?
        ");

        $stmt->execute([$hash, $data['email']]);

        $del = $conn->prepare("
            DELETE FROM password_resets 
            WHERE token=?
        ");

        $del->execute([$hashed_token]);

        header("Location: /PHP/Restaurant/index.php?reset=success");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Bootstrap -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow border-0 rounded-4 p-4" style="width:100%; max-width:420px;">

        <div class="text-center mb-4">

            <h2 class="fw-bold">
                Reset Password
            </h2>

            <p class="text-muted small mb-0">
                Enter your new password below
            </p>

        </div>

        <form method="POST">

            <!-- Password -->
            <div class="mb-3">

                <label class="form-label fw-semibold">
                    New Password
                </label>

                <input 
                    type="password"
                    name="password"
                    class="form-control rounded-3 py-2"
                    placeholder="Enter new password"
                    required
                >

            </div>

            <!-- Confirm Password -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Confirm Password
                </label>

                <input 
                    type="password"
                    name="confirm_password"
                    class="form-control rounded-3 py-2"
                    placeholder="Confirm new password"
                    required
                >

            </div>

 <?php if(!empty($err)): ?>

    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">

        <?php echo $err; ?>


    </div>

<?php endif; ?>
            <!-- Button -->
            <button 
                type="submit"
                class="btn btn-dark w-100 rounded-3 py-2 fw-semibold"
            >
                Reset Password
            </button>

        </form>

    </div>

</body>
</html>