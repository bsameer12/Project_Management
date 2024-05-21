<?php
 include("admin_session.php");
include("../connection/connection.php");


// Fetch data from both tables
$sql = "SELECT T.USER_ID, T.TRADER_ID, T.VERFIED_ADMIN, T.VERIFICATION_SEND, S.SHOP_NAME, S.SHOP_PROFILE, S.VERIFIED_SHOP, S.REGISTRATION_NO, S.SHOP_DESCRIPTION, S.SHOP_CATEGORY_ID 
        FROM trader T
        JOIN shop S ON T.USER_ID = S.USER_ID
        WHERE T.VERFIED_ADMIN = 0";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$data = [];
while ($row = oci_fetch_assoc($stmt)) {
    // Add data to the array
    $data[] = $row;
}

oci_free_statement($stmt);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verifyForm'])) {
    // Get the trader ID from the form
    $trader_id = $_POST['trader_id'];
    
    // Update VERFIED_ADMIN to 1
    $sqlUpdateAdmin = "UPDATE trader SET VERFIED_ADMIN = 1 WHERE TRADER_ID = :trader_id";
    $stmtUpdateAdmin = oci_parse($conn, $sqlUpdateAdmin);
    oci_bind_by_name($stmtUpdateAdmin, ':trader_id', $trader_id);
    oci_execute($stmtUpdateAdmin);
    oci_free_statement($stmtUpdateAdmin);

    // Prepare the SQL statement
        $sqlUserId = "SELECT USER_ID FROM TRADER WHERE TRADER_ID = :trader_id";

        // Prepare the statement
        $stmtUserId = oci_parse($conn, $sqlUserId);

        // Bind the parameter
        oci_bind_by_name($stmtUserId, ':trader_id', $trader_id);

        // Execute the statement
        oci_execute($stmtUserId);

        // Fetch the result
        $rowUserId = oci_fetch_assoc($stmtUserId);

        // Store the USER_ID in a variable
        $user_id = $rowUserId['USER_ID'];

        // Free the statement
        oci_free_statement($stmtUserId);


    // Update VERIFIED_SHOP to 1
    $sqlUpdateShop = "UPDATE shop SET VERIFIED_SHOP = 1 WHERE USER_ID = :user_id";
    $stmtUpdateShop = oci_parse($conn, $sqlUpdateShop);
    oci_bind_by_name($stmtUpdateShop, ':user_id', $user_id);
    oci_execute($stmtUpdateShop);
    oci_free_statement($stmtUpdateShop);

    // Redirect back to the same page after updating
    // Query to fetch USER_EMAIL, FIRST_NAME, and LAST_NAME based on TRADER_ID
        $sql = "SELECT U.USER_EMAIL, U.FIRST_NAME, U.LAST_NAME, T.TRADER_TYPE, T.SHOP_NAME, S.SHOP_ID
        FROM TRADER T
        JOIN HUDDER_USER U ON T.USER_ID = U.USER_ID
        JOIN SHOP S ON T.USER_ID = S.USER_ID
        WHERE T.TRADER_ID = :trader_id";


            // Prepare the statement
            $stmt = oci_parse($conn, $sql);

            // Bind the trader_id parameter
            oci_bind_by_name($stmt, ':trader_id', $trader_id);

            // Execute the statement
            oci_execute($stmt);

            // Fetch the result
            $row = oci_fetch_assoc($stmt);

            // Check if a row is fetched
            if ($row) {
            // Assign values to variables
            $user_email = $row['USER_EMAIL'];
            $first_name = $row['FIRST_NAME'];
            $last_name = $row['LAST_NAME'];
            $shop_name = $row['SHOP_NAME'];
            $shop_category = $row['TRADER_TYPE'];
            $shop_id = $row['SHOP_ID'];
            require("../PHPMailer-master/trader_verify_email.php");
            $name = $first_name . " " . $last_name;
            sendApprovalEmail($user_email, $name, $shop_id, $trader_id, $shop_name , $shop_category);
            } else {
            // Handle case where no matching record is found
            echo "No record found for the provided TRADER_ID.";
            }

            // Free statement and close connection
            oci_free_statement($stmt);
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }

oci_close($conn);


// Now $data contains data from both tables in the same array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Verification</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_customer.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Link to fontawesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php
        include("admin_navbar.php");
    ?>
    <h1 class="page-title">Trader Verification</h1>
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Trader Id</th> 
                    <th> Verification Status </th>
                    <th> Shop Name</th>
                    <th> Shop Profile </th>
                    <th> Regristation No</th>
                    <th> Shop Description</th>
                    <th> Shop Category</th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
                <?php
                foreach ($data as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['TRADER_ID'] . "</td>";
                    ?>
                    <td>
                    <form action="" method="POST">
                                <input type="hidden" name="trader_id" value="<?php echo $row['TRADER_ID']; ?>">
                                <input type="hidden" name="verifyForm">
                                <select name="admin_verified" onchange="this.form.submit()">
                                    <option value="0" <?php echo ($row['VERFIED_ADMIN'] == 0) ? 'selected' : ''; ?>>Unverified</option>
                                    <option value="1" <?php echo ($row['VERFIED_ADMIN'] == 1) ? 'selected' : ''; ?>>Verified</option>
                                </select>
                            </form>
                </td>

                    <?php
                    echo "<td>" . $row['SHOP_NAME'] . "</td>";
                    // Display the image if SHOP_PROFILE contains the image URL
                    echo "<td><img src='../shop_profile_image/" . $row['SHOP_PROFILE'] . "' alt='Shop Profile' style='width:50px;height:50px;'></td>";
                    echo "<td>" . $row['REGISTRATION_NO'] . "</td>";
                    echo "<td>" . $row['SHOP_DESCRIPTION'] . "</td>";
                    echo "<td>" . $row['SHOP_CATEGORY_ID'] . "</td>";
                    echo "<td> <a href='admin_view_trader.php?id=" . $row['USER_ID'] . "&action=edit'>View</a> | <a href='admin_view_trader.php?id=" . $row['USER_ID'] . "&action=edit'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
                
        </tbody>
        </table>
    </div>
    <script src="admin_customer.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    </script>
    <script>
        let table = new DataTable('#myTable', {
        responsive: true,
        });
    </script>
</body>
</html>