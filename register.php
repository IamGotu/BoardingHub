<?php
session_start();
require_once "config.php";

if (isset($_SESSION['username'])) {
    if ($_SESSION["admin"] == 'YES') {
        header("location: dashboard.php");
    } else {
        header("location: home.php");
    }
    exit();
}

$fname = $lname = $email = $username = $password = $confirm_password = "";
$fname_err = $lname_err = $email_err = $username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['fname']))) {
        $fname_err = "First Name cannot be blank";
    } else {
        $fname = trim($_POST['fname']);
    }

    if (empty(trim($_POST['lname']))) {
        $lname_err = "Last Name cannot be blank";
    } else {
        $lname = trim($_POST['lname']);
    }

    if (empty(trim($_POST['email']))) {
        $email_err = "Email cannot be blank";
    } else {
        $sql = "SELECT id FROM loginform WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST['email']);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken";
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo "<script>alert('Something went wrong');</script>";
            }
        }
    }

    if (empty(trim($_POST['username']))) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT id FROM loginform WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST['username']);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "<script>alert('Something went wrong');</script>";
            }
        }
    }

    if (empty(trim($_POST['password']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = "Password cannot be less than 8 characters";
    } else {
        $password = trim($_POST['password']);
    }

    if (trim($_POST['password']) != trim($_POST['confirm_password'])) {
        $confirm_password_err = "Passwords should match";
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($fname_err) && empty($lname_err)) {
        $sql = "INSERT INTO loginform (fname, lname, email, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $param_fname, $param_lname, $param_email, $param_username, $param_password);
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "<script>alert('Something went wrong.. Cannot Redirect');</script>";
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = $fname_err ?: $lname_err ?: $email_err ?: $username_err ?: $password_err ?: $confirm_password_err;
        echo "<script>alert('$error_msg')</script>";
    }
    mysqli_close($conn);
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
    <title>Boarding Hub - Register</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Boarding Hub</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="register.php">Register <i class="fas fa-user-plus"></i></a>
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

<div class="container register-container">
    <h2 class="text-center">Please Register</h2>
    <form action="" method="post">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputFName" class="form-label">First Name</label>
                <input type="text" class="form-control" name="fname" id="inputFName" placeholder="Enter first name">
            </div>
            <div class="col-md-6">
                <label for="inputLName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lname" id="inputLName" placeholder="Enter last name">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Enter email">
            </div>
            <div class="col-md-6">
                <label for="inputUname" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="inputUname" placeholder="Enter username">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Enter password">
            </div>
            <div class="col-md-6">
                <label for="inputConfirmPassword4" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="inputConfirmPassword4" placeholder="Confirm password">
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-custom">Sign Up</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
