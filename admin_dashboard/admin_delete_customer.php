<?php
include("../connection/connection.php");

// Get the user_id from the URL
$user_id = $_GET['id'] ?? null;

if ($user_id) {
    // Start a transaction
    oci_execute(oci_parse($conn, "BEGIN"), OCI_NO_AUTO_COMMIT);

    // List of tables from which to delete the user
    $tables = ["ORDERS", "CUSTOMER", "HUDDER_USER"];
    
    // Loop through each table and delete the user
    foreach ($tables as $table) {
        $sql = "DELETE FROM $table WHERE USER_ID = :user_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        
        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            $error = oci_error($stmt);
            oci_rollback($conn);
            echo "Error deleting from $table: " . $error['message'];
            exit();
        }

        oci_free_statement($stmt);
    }

    // Commit the transaction
    if (!oci_commit($conn)) {
        $error = oci_error($conn);
        echo "Error committing transaction: " . $error['message'];
        exit();
    }

    // Close the connection
    oci_close($conn);

    // Redirect to admin_customer.php
    header("Location: admin_customer.php");
    exit();
} else {
    echo "User ID is required.";
    exit();
}
?>

