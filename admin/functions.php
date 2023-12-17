<?php
$connection = mysqli_connect("localhost", "root", "", "LMS");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

function get_user_count() {
    global $connection;
    $user_count = 0;
    $query = "SELECT COUNT(*) AS user_count FROM users";
    $query_run = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($query_run)) {
        $user_count = $row['user_count'];                                                                  
    }
    return $user_count;
}


function get_issue_book_count() {
    global $connection;
    $issue_book_count = 0;
    $query = "SELECT COUNT(*) AS issue_book_count FROM issued_books";
    $query_run = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($query_run)) {
        $issue_book_count = $row['issue_book_count'];
    }
    return $issue_book_count;
}


function get_stored_procedures_count() {
    global $connection;

    $result = mysqli_query($connection, "SHOW PROCEDURE STATUS WHERE Db = 'LMS'");

    if (!$result) {
        die("Error: " . mysqli_error($connection));
    }

    $count = mysqli_num_rows($result);

    return $count;
}

function select_all_books() {
    global $connection;

    $procedureName = "SelectAllBooks";
    $sql = "CALL $procedureName()";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        $error = mysqli_error($connection);
        return json_encode(["error" => $error]);
    }

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return json_encode(["books" => $books]);
}


function select_all_authors() {
    global $connection;

    $procedureName = "SelectAllAuthors";
    $sql = "CALL $procedureName()";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        $error = mysqli_error($connection);
        return json_encode(["error" => $error]);
    }

    $authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return json_encode(["authors" => $authors]);
}



function select_all_categories() {
    global $connection;

    $procedureName = "SelectAllCategories";
    $sql = "CALL $procedureName()";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        $error = mysqli_error($connection);
        return json_encode(["error" => $error]);
    }

    $authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return json_encode(["categories" => $authors]);
}



function insert_book($book_name, $author_id, $cat_id, $ISBN)
{
    global $connection;

    $procedureName = "insert_book";
    $sql = "CALL $procedureName('$book_name', $author_id, $cat_id, '$ISBN')";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        return ["error" => "Error: " . mysqli_error($connection)];
    }

    return ["success" => "Book inserted successfully!"];
}



function update_book($bookId, $bookName, $authorId, $catId, $ISBN) {
    global $connection;

    $query = "CALL UpdateBook(?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    
    mysqli_stmt_bind_param($stmt, "issis", $bookId, $bookName, $authorId, $catId, $ISBN);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['message'];
}





function delete_book($book_id)
{
    global $connection;

    $procedureName = "delete_book";
    $sql = "CALL $procedureName($book_id)";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error: " . mysqli_error($connection));
    }

    $row = mysqli_fetch_assoc($result);

    return $row['result']; 
}

?>
