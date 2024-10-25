<?php 
require('./database.php'); // Include your database connection

// Prepare the initial query to fetch all records
$queryFetch = "SELECT * FROM kwm";

// Check if a search term is provided
if (isset($_GET['search_term'])) {
    $searchTerm = mysqli_real_escape_string($connection, $_GET['search_term']);
    $queryFetch .= " WHERE ID = '$searchTerm' OR FirstName LIKE '%$searchTerm%' OR LastName LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%'";
}

// Fetch records from the database
$result = mysqli_query($connection, $queryFetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>View Records</title>
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            color: #343a40; /* Dark text */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
        }
        .navbar {
            background-color: #343a40; /* Dark navbar */
        }
        .table-container {
            background-color: #ffffff; /* White background for the table */
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
        }
        .search-container {
            margin-bottom: 20px; /* Space between search and table */
        }
        .btn-primary, .btn-danger, .btn-warning {
            transition: background-color 0.3s; /* Smooth transition */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .btn-danger:hover {
            background-color: #c82333; /* Darker red on hover */
        }
        .btn-warning:hover {
            background-color: #e0a800; /* Darker yellow on hover */
        }
        @media (max-width: 576px) {
            .search-container input {
                width: 100%; /* Full width on small screens */
            }
            .search-container button {
                width: 100%; /* Full width on small screens */
                margin-top: 10px; /* Space between input and button */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <a class="navbar-brand" href="#">ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="admin.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="create_account.php">Create Account</a></li>
                <li class="nav-item"><a class="nav-link" href="view_records.php">View Records</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Account Records</h2>

        <div class="search-container text-center">
            <form action="view_records.php" method="GET" class="form-inline justify-content-center">
                <input type="text" name="search_term" placeholder="Search by ID, Name, or Email" class="form-control mb-2 mr-2" style="width: 300px;" required>
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>

        <div class="search-container text-center">
            <form action="delete.php" method="POST" class="form-inline justify-content-center">
                <input type="text" name="deleteID" placeholder="Enter ID to Delete" class="form-control mb-2 mr-2" required>
                <button type="submit" name="delete" class="btn btn-danger mb-2">Delete</button>
            </form>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if the query was successful
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['ID']}</td>
                                            <td>{$row['FirstName']}</td>
                                            <td>{$row['MiddleName']}</td>
                                            <td>{$row['LastName']}</td>
                                            <td>{$row['Email']}</td>
                                            <td><a href='edit.php?id={$row['ID']}' class='btn btn-warning btn-sm'>Edit</a></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Error retrieving records</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
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
