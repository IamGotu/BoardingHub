<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    if ($_SESSION["admin"] == 'YES') {
        header("location: dashboard.php");
    } else {
        header("location: home.php");
    }
    exit();
}

require_once "config.php";

$username = $password = "";
$err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
        $err = "Please enter both username and password";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $sql = "SELECT id, fname, lname, email, username, password, admin, created_at FROM loginform WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $fname, $lname, $email, $username, $hashed_password, $admin, $created_at);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct. Allow user to login
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["fname"] = $fname;
                        $_SESSION["lname"] = $lname;
                        $_SESSION["email"] = $email;
                        $_SESSION["id"] = $id;
                        $_SESSION["loggedin"] = true;
                        // Redirect the user to the accountInfo page
                        $_SESSION["admin"] = $admin;
                        $_SESSION["created_at"] = $created_at;
                        if ($_SESSION["admin"] == 'YES') {
                            header("location: dashboard.php");
                        } else {
                            header("location: home.php");
                        }
                    } else {
                        $err = "Incorrect password";
                        echo "<script>alert('$err');</script>";
                    }
                }
            } else {
                $err = "An account with that username does not exist";
                echo "<script>alert('$err');</script>";
            }
        } else {
            $err = "Oops! Something went wrong. Please try again later.";
            echo "<script>alert('$err');</script>";
        }
        mysqli_stmt_close($stmt);
    }
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
    <title>Boarding Hub - Login</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Boarding Hub</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register <i class="fas fa-user-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="login.php">Login <i class="fas fa-sign-in-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact <i class="fas fa-envelope"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container login-container">
    <h2 class="text-center">Please Login</h2>
    <form action="" method="post">
        <div class="mb-3">
            <label for="inputUname" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="inputUname" placeholder="Enter username">
        </div>
        <div class="mb-3">
            <label for="inputPassword" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Enter password">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" name="login" class="btn btn-custom">Log In</button>
            <button type="button" name="forgotPassword" class="btn btn-forgot" onclick="window.location.href='forgotPassword.php'">Forgot Password</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-B4gt1jrGC7Jh4PEtcN9G3KMLP1AamPRtm9hgkrweE28yfF2gpCJOwOu1rZeG1jz5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9JtD54VV4m1wEYfmh7tUaVgIM4VHNP8LvwVeq4fFZC+hVU4f/3dMOhO" crossorigin="anonymous"></script>
</body>
</html>
