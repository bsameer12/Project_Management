<?php
// Include the database connection
include("../connection/connection.php");

// Get the SHOP_ID from the request (assuming it's passed via GET or POST)
$shop_id = $_GET['id'] ?? $_POST['id'];

// Check if SHOP_ID is set
if (isset($shop_id)) {
    // Begin a transaction
    oci_execute(oci_parse($conn, "BEGIN"));

    try {
        // Retrieve the USER_ID from the SHOP table
        $selectUserIdSql = "SELECT USER_ID FROM SHOP WHERE SHOP_ID = :shop_id";
        $selectUserIdStmt = oci_parse($conn, $selectUserIdSql);
        oci_bind_by_name($selectUserIdStmt, ':shop_id', $shop_id);
        oci_execute($selectUserIdStmt);
        $userIdRow = oci_fetch_assoc($selectUserIdStmt);

        if (!$userIdRow) {
            throw new Exception("SHOP_ID not found in SHOP table.");
        }

        $user_id = $userIdRow['USER_ID'];

        // Delete from HUDDER_USER table where USER_ID matches
        $deleteHudderUserSql = "DELETE FROM HUDDER_USER WHERE USER_ID = :user_id";
        $deleteHudderUserStmt = oci_parse($conn, $deleteHudderUserSql);
        oci_bind_by_name($deleteHudderUserStmt, ':user_id', $user_id);
        $deleteHudderUserSuccess = oci_execute($deleteHudderUserStmt, OCI_NO_AUTO_COMMIT);

        if (!$deleteHudderUserSuccess) {
            $e = oci_error($deleteHudderUserStmt);
            throw new Exception("Failed to delete from HUDDER_USER table: " . htmlentities($e['message']));
        }

        // Delete from SHOP table where SHOP_ID matches
        $deleteShopSql = "DELETE FROM SHOP WHERE SHOP_ID = :shop_id";
        $deleteShopStmt = oci_parse($conn, $deleteShopSql);
        oci_bind_by_name($deleteShopStmt, ':shop_id', $shop_id);
        $deleteShopSuccess = oci_execute($deleteShopStmt, OCI_NO_AUTO_COMMIT);

        if (!$deleteShopSuccess) {
            $e = oci_error($deleteShopStmt);
            throw new Exception("Failed to delete from SHOP table: " . htmlentities($e['message']));
        }

        // Commit the transaction if both deletes were successful
        oci_commit($conn);

        // Free statement resources
        oci_free_statement($selectUserIdStmt);
        oci_free_statement($deleteHudderUserStmt);
        oci_free_statement($deleteShopStmt);

        echo "Records deleted successfully.";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        oci_rollback($conn);
        echo $e->getMessage();
    } finally {
        // Close the connection
        oci_close($conn);
        header("Location: admin_shop_detail.php");
    }
} else {
    echo "SHOP_ID must be provided.";
}
?>
