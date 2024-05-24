<?php
include("admin_session.php");
include("../connection/connection.php");

// Get the user_id from the URL
$user_id = $_GET['id'] ?? null;


// Prepare the SQL statement
$sql = "DELETE FROM REVIEW WHERE REVIEW_ID = :user_id";

// Parse the SQL
$statement = oci_parse($conn, $sql);

if (!$statement) {
    $error = oci_error($conn);
    trigger_error(htmlentities($error['message'], ENT_QUOTES), E_USER_ERROR);
}

// Bind the user_id parameter
oci_bind_by_name($statement, ':user_id', $user_id);

// Execute the statement
$result = oci_execute($statement, OCI_DEFAULT);

if (!$result) {
    $error = oci_error($statement);
    trigger_error(htmlentities($error['message'], ENT_QUOTES), E_USER_ERROR);
}

// Commit the transaction
oci_commit($conn);

// Free the statement and close the connection
oci_free_statement($statement);
oci_close($conn);

header("Location: admin_review_rating.php");
?>