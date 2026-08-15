<?php
include '../config/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../src/PHPMailer.php';
require __DIR__ . '/../../src/SMTP.php';
require __DIR__ . '/../../src/Exception.php';
date_default_timezone_set('Asia/Kolkata');
$message='';
$err='';
if($_SERVER['REQUEST_METHOD']=='POST'){
$email=$_POST['email'];
$stmt=$conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$get_mail=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$get_mail){
    $message = "If an account exists, a reset link has been sent.";
}
else{
    $token=bin2hex(random_bytes(16));
    $hashed_token=hash('sha256',$token);
    $expiresAt=date("Y-m-d H:i:s", strtotime("+10 minutes"));
    $old=$conn->prepare("DELETE FROM password_resets WHERE email=?");
$old->execute([$email]);
    $insert=$conn->prepare("INSERT INTO password_resets(email,token,expires_at)
    VALUES(?,?,?)");
    $insert->execute([$email,$hashed_token,$expiresAt]);

    $mail=new PHPMailer(true);
    try{
        $mail->isSMTP();
        $mail->Host='smtp.gmail.com';
        $mail->SMTPAuth=true;
        $mail->Username=SMTP_USER;
        $mail->Password=SMTP_PASS;
        $mail->Port=587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom('ranashivangi43@gmail.com','Shivangi');
        $mail->addAddress($email);
        $mail->Subject='Verify to change password';
        $mail->isHTML(true);
$mail->Body = "
<html>
<head>
    <style>
        body{
            margin:0;
            padding:0;
            background:#f4f4f4;
            font-family:Arial, sans-serif;
        }

        .container{
            max-width:500px;
            margin:40px auto;
            background:#ffffff;
            padding:40px 30px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.08);
        }

        h1{
            color:#222;
            margin-bottom:10px;
        }

        p{
            color:#555;
            font-size:15px;
            line-height:1.6;
            margin-bottom:30px;
        }

        .btn{
            display:inline-block;
            padding:14px 28px;
            background:#000;
            color:#fff !important;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
        }

        .footer{
            margin-top:30px;
            font-size:12px;
            color:#888;
        }
    </style>
</head>

<body>

    <div class='container'>

        <h1>Password Reset</h1>

        <p>
            We received a request to reset your password.
            Click the button below to create a new password.
        </p>

        <a 
            href='http://localhost/PHP/Restaurant/process/reset_password.php?token=$token'
            class='btn'
        >
            Reset Password
        </a>

        <div class='footer'>
            This link will expire in 10 minutes.
        </div>

    </div>

</body>
</html>
";

       
        $mail->send();
         $message="Reset link sent to your email.";
    }
    catch(Exception $e){
        $err = "Unable to send reset email.";
    }
}
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card p-4 shadow-sm rounded-4" style="max-width: 400px; width: 100%;">
        <h2 class="text-center fw-bold mb-4">Forgot Password</h2>
        
        <form action="#" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control py-2 rounded-3" placeholder="Enter your email" required>
            </div>
            <?php if(!empty($message)): ?>

    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">

        <?php echo $message; ?>


    </div>

<?php endif; ?>
 <?php if(!empty($err)): ?>

    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">

        <?php echo $err; ?>


    </div>

<?php endif; ?>
            <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold">
                Submit
            </button>
           
        </form>
        <div class="text-center"> <a href="/PHP/Restaurant/index.php">Back to Login!</a></div>
    </div>

</body>
</html>
