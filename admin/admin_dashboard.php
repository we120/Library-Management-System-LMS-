<?php
require("functions.php");
session_start();
error_log('Received data: ' . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["execute_insert_procedure"])) {
    if (isset($_POST['book_name'], $_POST['author_id'], $_POST['cat_id'], $_POST['ISBN'])) {
        insert_book($_POST['book_name'], $_POST['author_id'], $_POST['cat_id'], $_POST['ISBN']);
        echo "Procedure executed successfully!";
        exit; 
    } else {
        echo "Error: Parameters are missing!";
        exit; 
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["execute_delete_procedure"])) {
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : null;

    if ($book_id !== null) {

        $deleteResult = delete_book($book_id);

        echo $deleteResult;
        exit; 
    } else {
        echo "Error: Invalid or missing book ID!";
        exit; 
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white">
    <span><strong>Welcome: <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?></strong></span>
</font>
<font style="color: white">
    <span><strong>Email: <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></strong></font>
</font>

            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
        <div class="container-fluid">
            <ul class="nav navbar-nav navbar-center">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Category </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_cat.php">Add New Category</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_cat.php">Manage Category</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Authors</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_author.php">Add New Author</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_author.php">Manage Author</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="issue_book.php">Issue Book</a>
                </li>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="audit_books.php">Audit Books</a>
                </li>
                <li class="nav-item">
		        <a class="nav-link" href="view_page.php">View Books</a>
		      </li>
              </li>
                <li class="nav-item">
		        <a class="nav-link" href="procedure.php">Procedure</a>
		      </li>
            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library mangement system. Library opens at 8:00 AM and close at 8:00 PM</marquee></span><br><br>
    <div class="row justify-content-center">

        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Registered User</div>
                <div class="card-body">
                    <p class="card-text">No. total Users: <?php echo get_user_count();?></p>
                    <a class="btn btn-danger" href="Regusers.php" target="_blank">View Registered Users</a>
                </div>
            </div>
        </div>


        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Book Issued</div>
                <div class="card-body">
                    <p class="card-text">No of book issued: <?php echo get_issue_book_count();?></p>
                    <a class="btn btn-success" href="view_issued_book.php" target="_blank">View Issued Books</a>
                </div>
            </div>
        </div>


</body>
</html>