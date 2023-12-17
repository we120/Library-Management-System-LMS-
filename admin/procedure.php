<?php
require("functions.php");
session_start();


if (isset($_POST["execute_select_all_books"])) {
    $books = select_all_books();

    // Check if there was an error in the procedure
    if ($books === "Error: " . mysqli_error($connection)) {
        echo json_encode(["error" => $books]);
    } else {
        echo $books;
    }
    exit;
}

if (isset($_POST["execute_select_all_authors"])) {
    $authors = select_all_authors();

    // Check if there was an error in the procedure
    if ($authors === "Error: " . mysqli_error($connection)) {
        echo json_encode(["error" => $authors]);
    } else {
        echo $authors;
    }
    exit;
}

if (isset($_POST["execute_select_all_categories"])) {
    $categories = select_all_categories();

    // Check if there was an error in the procedure
    if ($categories === "Error: " . mysqli_error($connection)) {
        echo json_encode(["error" => $categories]);
    } else {
        echo $categories;
    }
    exit;
}

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
    
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["execute_update_procedure"])) {
    // Check if all required parameters are set
    if (isset($_POST['book_id'], $_POST['book_name'], $_POST['author_id'], $_POST['cat_id'], $_POST['ISBN'])) {
        $bookId = $_POST['book_id'];
        $bookName = $_POST['book_name'];
        $authorId = $_POST['author_id'];
        $catId = $_POST['cat_id'];
        $ISBN = $_POST['ISBN'];

        // Call your update_book function
        $updateResult = update_book($bookId, $bookName, $authorId, $catId, $ISBN);

        // Debugging: Log information
        error_log("Update result: " . var_export($updateResult, true));

        // Check the result of the update operation
        if ($updateResult === true) {
            echo "Book updated successfully!";
        } else {
            echo "Error: " . $updateResult;
        }
    } else {
        echo "Error: Parameters are missing!";
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
	        		<a class="dropdown-item" href="">View Profile</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="#">Edit Profile</a>
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
              <li class="nav-item">
		        <a class="nav-link" href="audit_books.php">Audit Books</a>
		      </li>
              <li class="nav-item">
		        <a class="nav-link" href="view_page.php">View Books</a>
		      </li>
              <li class="nav-item">
		        <a class="nav-link" href="procedure.php">Procedure</a>
		      </li>
		    </ul>
		</div>
	</nav><br>
	<span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    <div class="row justify-content-center">



    <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-body">
                <button type="button" class="btn btn-info" id="executeSelectAllBooks">Select All Books</button>
                </div>
            </div>
        </div>


        <div class="col-md-3" style="margin: 0px">
    <div class="card bg-light" style="width: 300px">
        <div class="card-body">
            <button type="button" class="btn btn-info" id="executeSelectAllAuthors">Select All Authors</button>
        </div>
    </div>
</div>


        <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-body">
                     <button type="button" class="btn btn-info" id="executeSelectAllCategories">Select All Categories</button>
                </div>
            </div>
        </div>
        </div><br><br>






        <div class="row justify-content-center">
 
        <div class="col-md-3" style="margin: 0px">
        <div class="card bg-light" style="width: 300px">
            <div class="card-header">Insert Book</div>
            <div class="card-body">

            <form method="post" id="executeInsertProcedureForm">
                    <div class="form-group">
                        <label for="book_name">Book Name:</label>
                        <input type="text" class="form-control" id="book_name" name="book_name" placeholder="Enter Book Name" required>
                    </div>
                    <div class="form-group">
                        <label for="author_id">Author ID:</label>
                        <input type="text" class="form-control" id="author_id" name="author_id" placeholder="Enter Author ID" required>
                    </div>
                    <div class="form-group">
                        <label for="cat_id">Category ID:</label>
                        <input type="text" class="form-control" id="cat_id" name="cat_id" placeholder="Enter Category ID" required>
                    </div>
                    <div class="form-group">
                        <label for="ISBN">ISBN:</label>
                        <input type="text" class="form-control" id="ISBN" name="ISBN" placeholder="Enter ISBN" required>
                    </div>
                    <button type="button" class="btn btn-primary" id="executeInsertProcedure">Insert Book</button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-3" style="margin: 0px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Update Book</div>
                <div class="card-body">
                    <form method="post" id="executeUpdateProcedureForm">
                        <div class="form-group">
                            <label for="update_book_id">Book ID:</label>
                            <input type="text" class="form-control" id="update_book_id" name="update_book_id" placeholder="Enter Book ID" required>
                        </div>
                        <div class="form-group">
                            <label for="update_book_name">Book Name:</label>
                            <input type="text" class="form-control" id="update_book_name" name="update_book_name" placeholder="Enter Book Name" required>
                        </div>
                        <div class="form-group">
                            <label for="update_author_id">Author ID:</label>
                            <input type="text" class="form-control" id="update_author_id" name="update_author_id" placeholder="Enter Author ID" required>
                        </div>
                        <div class="form-group">
                            <label for="update_cat_id">Category ID:</label>
                            <input type="text" class="form-control" id="update_cat_id" name="update_cat_id" placeholder="Enter Category ID" required>
                        </div>
                        <div class="form-group">
                            <label for="update_ISBN">ISBN:</label>
                            <input type="text" class="form-control" id="update_ISBN" name="update_ISBN" placeholder="Enter ISBN" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="executeUpdateProcedure">Update Book</button>
                    </form>
                </div>
            </div>
        </div>




<div class="col-md-3" style="margin: 0px">
    <div class="card bg-light" style="width: 300px">
        <div class="card-header">Delete Book</div>
        <div class="card-body">

            <form method="post" id="executeDeleteProcedureForm">
                <div class="form-group">
                    <label for="delete_book_id">Book ID:</label>
                    <input type="text" class="form-control" id="delete_book_id" name="book_id" placeholder="Enter Book ID" required>
                </div>
				<button type="button" class="btn btn-danger" id="executeDeleteProcedure">Delete Book</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

       
        $("#executeSelectAllBooks").on("click", function () {
    var data = {
        execute_select_all_books: true,
    };

    var newTab = window.open("about:blank", "_blank");

    $.ajax({
        type: "POST",
        url: "procedure.php",
        data: data,
        dataType: "json",
        success: function (response) {
            if ("error" in response) {
                newTab.document.write("<p>Error: " + response.error + "</p>");
            } else {
                displayBooksInNewTab(newTab, response.books);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error executing SelectAllBooks procedure:", error);
        }
    });
});

function displayBooksInNewTab(tab, books) {
    // Clear any existing content in the new tab
    tab.document.body.innerHTML = "";

    // Check if books is an array
    if (Array.isArray(books) && books.length > 0) {
        // Create a table
        tab.document.write("<table border='1'>");
        tab.document.write("<tr><th>Book ID</th><th>Book Name</th><th>ISBN</th><th>Author Name</th><th>Category Name</th></tr>");

        // Iterate through the array and display book information
        books.forEach(function (book) {
            tab.document.write("<tr><td>" + book.book_id + "</td><td>" + book.book_name + "</td><td>" + book.ISBN + "</td><td>" + book.author_name + "</td><td>" + book.category_name + "</td></tr>");
        });

        tab.document.write("</table>");
    } else {
        tab.document.write("<p>No books found.</p>");
    }
}

// Your other JavaScript code for AJAX requests...





    
        $("#executeSelectAllAuthors").on("click", function () {
            var data = {
                execute_select_all_authors: true,
            };

            var newTab = window.open("about:blank", "_blank");

            $.ajax({
                type: "POST",
                url: "procedure.php",
                data: data,
                dataType: "json",
                success: function (response) {
                    if ("error" in response) {
                        newTab.document.write("<p>Error: " + response.error + "</p>");
                    } else {
                        displayAuthorsInNewTab(newTab, response.authors);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error executing SelectAllAuthors procedure:", error);
                }
            });
        });

        function displayAuthorsInNewTab(tab, authors) {
    // Clear any existing content in the new tab
    tab.document.body.innerHTML = "";

    // Check if authors is an array
    if (Array.isArray(authors) && authors.length > 0) {
        // Create a table
        tab.document.write("<table border='1'>");
        tab.document.write("<tr><th>Author ID</th><th>Author Name</th></tr>");

        // Iterate through the array and display author information
        authors.forEach(function (author) {
            tab.document.write("<tr><td>" + author.author_id + "</td><td>" + author.author_name + "</td></tr>");
        });

        tab.document.write("</table>");
    } else {
        tab.document.write("<p>No authors found.</p>");
    }
}


        // Your other JavaScript code for AJAX requests...

    });



$("#executeSelectAllCategories").on("click", function () {
    var data = {
        execute_select_all_categories: true,
    };

    var newTab = window.open("about:blank", "_blank");

    $.ajax({
        type: "POST",
        url: "procedure.php",
        data: data,
        dataType: "json",
        success: function (response) {
            if ("error" in response) {
                newTab.document.write("<p>Error: " + response.error + "</p>");
            } else {
                displayCategoriesInNewTab(newTab, response.categories);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error executing SelectAllCategories procedure:", error);
        }
    });
});

function displayCategoriesInNewTab(tab, categories) {
    // Clear any existing content in the new tab
    tab.document.body.innerHTML = "";

    // Check if categories is an array
    if (Array.isArray(categories) && categories.length > 0) {
        // Create a table
        tab.document.write("<table border='1'>");
        tab.document.write("<tr><th>Category ID</th><th>Category Name</th></tr>");

        // Iterate through the array and display category information
        categories.forEach(function (category) {
            tab.document.write("<tr><td>" + category.cat_id + "</td><td>" + category.category_name + "</td></tr>");
        });

        tab.document.write("</table>");
    } else {
        tab.document.write("<p>No categories found.</p>");
    }
}




$(document).ready(function () {

$("#executeInsertProcedure").on("click", function () {

var bookName = $("#book_name").val();
var authorId = $("#author_id").val();
var catId = $("#cat_id").val();
var ISBN = $("#ISBN").val();


var data = {
execute_insert_procedure: true,
book_name: bookName,
author_id: authorId,
cat_id: catId,
ISBN: ISBN,
};


$.ajax({
type: "POST",
url: "admin_dashboard.php",
data: data,
success: function (response) {
    alert(response);
},
error: function (error) {
    console.error("Error executing procedure:", error);
}
});
});



$(document).ready(function () {
    $("#executeUpdateProcedure").on("click", function () {
        var bookId = $("#update_book_id").val(); 
        var bookName = $("#update_book_name").val();
        var authorId = $("#update_author_id").val();
        var catId = $("#update_cat_id").val();
        var ISBN = $("#update_ISBN").val();

        var data = {
            execute_update_procedure: true,
            book_id: bookId, 
            book_name: bookName,
            author_id: authorId,
            cat_id: catId,
            ISBN: ISBN,
        };

        $.ajax({
            type: "POST",
            url: "procedure.php",
            data: data,
            success: function (response) {
                alert(response);
            },
            error: function (error) {
                console.error("Error executing update procedure:", error);
            }
        });
    });
});





        $("#executeDeleteProcedure").on("click", function () {
            var bookId = $("#delete_book_id").val();

            var data = {
                execute_delete_procedure: true,
                book_id: bookId,
            };

            $.ajax({
                type: "POST",
                url: "procedure.php",
                data: data,
                success: function (response) {
                    if (response.startsWith("Error:")) {
                        alert(response);
                    } else {
                        alert("Book deleted successfully!");
                    }
                },
                error: function (error) {
                    alert("Error executing procedure. Check console for details.");
                    console.error("AJAX error:", error);
                }
            });
        });
    });
</script>


</body>
</html>
