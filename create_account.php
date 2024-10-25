<?php
session_start(); // Start the session
require('./database.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $FName = trim($_POST['first_name']);
    $MName = trim($_POST['middle_name']);
    $LName = trim($_POST['last_name']);
    $Email = trim($_POST['email']);
    $Password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists
    $queryCheck = "SELECT * FROM kwm WHERE email = ?";
    $stmtCheck = mysqli_prepare($connection, $queryCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $Email);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        echo '<script>alert("Email already exists. Please choose another.")</script>';
    } else {
        // Prepare the SQL statement to insert new record
        $queryCreate = "INSERT INTO kwm (FirstName, MiddleName, LastName, Email, Password, role) VALUES (?, ?, ?, ?, ?, 'user')";
        $stmtCreate = mysqli_prepare($connection, $queryCreate);
        
        // Bind parameters
        mysqli_stmt_bind_param($stmtCreate, "sssss", $FName, $MName, $LName, $Email, $Password);

        // Execute the statement
        if (mysqli_stmt_execute($stmtCreate)) {
            echo '<script>alert("Account successfully created.");</script>';
            echo '<script>window.location.href = "create_account.php";</script>'; // Redirect
        } else {
            echo '<script>alert("Error: ' . mysqli_error($connection) . '")</script>';
        }

        // Close the statement
        mysqli_stmt_close($stmtCreate);
    }

    // Close the check statement
    mysqli_stmt_close($stmtCheck);
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Account</title>
    <style>
        body {
            background-color: grey; /* Background color */
            color: white; /* Text color */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Create Account</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>
        <p class="mt-3"><a href="login.php" class="text-light">Already have an account? Log in here.</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
