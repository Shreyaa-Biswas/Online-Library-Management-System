<?php
require_once('PhpConnect.php');

// Check if book_ids parameter is passed through POST method
if (isset($_POST['book_ids'])) {
    $book_ids = explode(',', $_POST['book_ids']);

    // Begin a transaction
    mysqli_begin_transaction($conn);

    foreach ($book_ids as $book_id) {
        // Update Book table
        $updateBookQuery = "UPDATE Book SET Member_ID = NULL, Issue_Date = NULL, Return_date = NULL, Fine = NULL WHERE Book_ID = '$book_id'";
        if (!mysqli_query($conn, $updateBookQuery)) {
            mysqli_rollback($conn);
            echo "Error updating Book record: " . mysqli_error($conn);
            exit; // Stop execution on error
        }

        // Update Book_Copy table
        $updateStatusQuery = "UPDATE Book_Copy SET Status = 1 WHERE Book_ID = '$book_id'";
        if (!mysqli_query($conn, $updateStatusQuery)) {
            mysqli_rollback($conn);
            echo "Error updating Book_Copy record: " . mysqli_error($conn);
            exit; // Stop execution on error
        }
    }

    // Commit the transaction
    mysqli_commit($conn);

    echo "Books returned successfully.";
    exit; // Make sure to exit after the header to prevent further execution
}
?>