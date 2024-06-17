<?php
require_once "config.php";
session_start();

$name = $email = $message = "";
$name_err = $email_err = $message_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Name validation
    if (empty(trim($_POST['name']))) {
        $name_err = "Name cannot be blank";
    } else {
        $name = trim($_POST['name']);
    }

    // Message validation
    if (empty(trim($_POST['message']))) {
        $message_err = "Message cannot be blank";
    } else {
        $message = trim($_POST['message']);
    }
    
    // Email validation
    if (empty(trim($_POST['email']))) {
        $email_err = "Email cannot be blank";
    } else {
        $email = trim($_POST['email']);
    }

    // If there were no errors, insert the values into the database
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        $sql = "INSERT INTO contactform (name, email, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Submitted Successfully')</script>";
            } else {
                echo "<script>alert('Something went wrong.. Cannot Redirect');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo 'Something went wrong';
        }
    } else {
        if (!empty($name_err)) {
            echo "<script>alert('$name_err')</script>";
        } elseif (!empty($email_err)) {
            echo "<script>alert('$email_err')</script>";
        } elseif (!empty($message_err)) {
            echo "<script>alert('$message_err')</script>";
        }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Boarding Hub - Contact Us</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Boarding Hub</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="forgotPassword.php">Reset Password <i class="fas fa-pencil-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register <i class="fas fa-user-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login <i class="fas fa-sign-in-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="contact.php">Contact <i class="fas fa-envelope"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container reset-container">
    <h2 class="text-center">Contact Us</h2>
    <form action="" method="post">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputName" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="inputName" placeholder="Enter your name">
            </div>
            <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Enter your email">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="inputMessage" class="form-label">Message</label>
                <textarea class="form-control" name="message" id="inputMessage" rows="5" placeholder="Enter your message"></textarea>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-custom">Submit</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-B4gt1jrGC7Jh4PEtcN9G3KMLP1AamPRtm9hgkrweE28yfF2gpCJOwOu1rZeG1jz5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9JtD54VV4m1wEYfmh7tUaVgIM4VHNP8LvwVeq4fFZC+hVU4f/3dMOhO" crossorigin="anonymous"></script>
</body>
</html>
