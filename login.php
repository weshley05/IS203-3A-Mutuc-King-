<?php
session_start();
require('./database.php'); // Include your database connection

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = trim($_POST['login_input']); // This will be the email
    $Password = $_POST['password'];

    // Prepare the SQL query
    $query = "SELECT id, email, password, role FROM kwm WHERE email = ?";

    if ($stmt = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $loginInput);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $email, $hashedPassword, $role);
        
        if (mysqli_stmt_fetch($stmt)) {
            // Verify password
            if (password_verify($Password, $hashedPassword)) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                // Redirect to admin.php only if the email is admin@gmail.com
                if ($email === 'admin@gmail.com') {
                    echo '<script>alert("Admin Login Successful!"); window.location.href = "admin.php";</script>';
                } else {
                    echo '<script>alert("Login Successful!"); window.location.href = "userpage.php";</script>';
                }
            } else {
                $errorMessage = "Invalid password.";
            }
        } else {
            $errorMessage = "No user found with that email.";
        }
        mysqli_stmt_close($stmt);
    } else {
        // Log error instead of displaying it
        error_log("SQL error: " . mysqli_error($connection));
        $errorMessage = "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            background-color: #f0f2f5;
            color: #333;
            height: 100vh;
        }
        .login-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 10px;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const checkbox = document.getElementById('show-password');
            passwordInput.type = checkbox.checked ? 'text' : 'password';
        }
    </script>
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="container">
        <h2 class="text-center">Login</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="login_input">Email</label>
                            <input type="email" class="form-control" name="login_input" id="login_input" placeholder="Enter your email" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="show-password" onclick="togglePassword()">
                            <label class="form-check-label" for="show-password">Show Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger mt-3" role="alert"><?= htmlspecialchars($errorMessage) ?></div>
                        <?php endif; ?>
                    </form>
                    <p class="mt-3 text-center"><a href="create_account.php" class="text-primary">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
