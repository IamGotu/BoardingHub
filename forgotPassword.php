<?php
require_once "config.php";

$email = $username = $password = $confirm_password = "";
$err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['username'])) || empty(trim($_POST['password'])) || empty(trim($_POST['email'])) || empty(trim($_POST['confirm_password']))) {
        $err = "Please enter all details";
    } else {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
    }
}

if (empty($err)) {
    $sql = "SELECT email, username, password FROM loginform where username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $email, $username, $hashed_password);
            if (mysqli_stmt_fetch($stmt)) {
                session_start();
                if ($email == $_POST['email']) {
                    if (empty(trim($_POST['password']))) {
                        $password_err = "Password cannot be blank";
                    } elseif (strlen(trim($_POST['password'])) < 5) {
                        $password_err = "Password cannot be less than 5 characters";
                    } else {
                        $password = trim($_POST['password']);
                    }

                    if (trim($_POST['password']) != trim($_POST['confirm_password'])) {
                        $confirm_password_err = "Passwords should match";
                    }
                    if (empty($password_err)) {
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "UPDATE loginform SET password='$password' where username='$username'";
                        $stmt = mysqli_query($conn, $sql);
                        header("location: login.php");
                    } else {
                        echo "<script>alert('$password_err')</script>";
                    }
                } else {
                    $err = "Wrong Email address";
                    echo "<script>alert('$err')</script>";
                }
            }
        }
    }
} else {
    echo "<script>alert('$err')</script>";
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
    <title>Boarding Hub - Reset Password</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Boarding Hub</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="forgotPassword.php">Reset Password <i class="fas fa-pencil-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register <i class="fas fa-user-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login <i class="fas fa-sign-in-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact <i class="fas fa-envelope"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container reset-container">
    <h2 class="text-center">Reset Password</h2>
    <form action="" method="post">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputEmail3" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="Enter email">
            </div>
            <div class="col-md-6">
                <label for="inputUname" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="inputUname" placeholder="Enter username">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputPassword3" class="form-label">New Password</label>
                <input type="password" class="form-control" name="password" id="inputPassword3" placeholder="Enter new password">
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="inputPassword4" placeholder="Confirm new password">
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-custom">Reset Password</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-B4gt1jrGC7Jh4PEtcN9G3KMLP1AamPRtm9hgkrweE28yfF2gpCJOwOu1rZeG1jz5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9JtD54VV4m1wEYfmh7tUaVgIM4VHNP8LvwVeq4fFZC+hVU4f/3dMOhO" crossorigin="anonymous"></script>
</body>
</html>
