<?php
session_start(); // Start the session
require('./database.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not authenticated
    header("Location: login.php");
    exit();
}

// Initialize $row to prevent undefined variable notice
$row = null;

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    
    // Fetch the user record based on the ID
    $query = "SELECT * FROM kwm WHERE ID = '$id'";
    $result = mysqli_query($connection, $query);

    // Check if the query was successful and if a record was found
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo '<script>alert("No record found for this ID."); window.location.href="view_records.php";</script>';
        exit; // Stop further execution
    }
}

// Handle form submission for updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($connection, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Update query
    $updateQuery = "UPDATE kwm SET FirstName=?, MiddleName=?, LastName=?, Email=? WHERE ID=?";
    if ($stmt = mysqli_prepare($connection, $updateQuery)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $firstName, $middleName, $lastName, $email, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo '<script>alert("Record updated successfully!"); window.location.href="view_records.php";</script>';
        } else {
            echo '<script>alert("Error updating record: ' . mysqli_error($connection) . '");</script>';
        }
    } else {
        echo '<script>alert("Error preparing statement.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Record</title>
    <style>
        body {
            background-color: grey; /* Background color */
            color: white; /* Text color */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Record</h2>
        <form method="POST" action="edit.php?id=<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo isset($row['FirstName']) ? htmlspecialchars($row['FirstName']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo isset($row['MiddleName']) ? htmlspecialchars($row['MiddleName']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo isset($row['LastName']) ? htmlspecialchars($row['LastName']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($row['Email']) ? htmlspecialchars($row['Email']) : ''; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Record</button>
            <a href="userpage.php" class="btn btn-secondary">Previous</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>
