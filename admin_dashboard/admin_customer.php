<?php
 include("admin_session.php");
// Initialize connection and other necessary variables
include("../connection/connection.php");
// Construct the SQL statement
$sql = "SELECT 
HU.*
FROM 
HUDDER_USER HU
JOIN 
CUSTOMER C ON HU.USER_ID = C.USER_ID
WHERE 
HU.USER_TYPE = 'customer' 
AND C.VERIFIED_CUSTOMER = 1";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

// Execute the statement
oci_execute($stmt);

// Initialize an array to store the results
$users = array();

// Fetch the results into the array
while ($row = oci_fetch_assoc($stmt)) {
    $users[] = $row;
}

// Free the statement
oci_free_statement($stmt);

// Close the connection
oci_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
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
    <h1 class="page-title">Customer Details</h1>
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Customer ID </th> 
                    <th> Profile Picture  </th>
                    <th> Name</th>
                    <th> Address </th>
                    <th> Email </th>
                    <th> Contact</th>
                    <th> Age </th>
                    <th> Gender </th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['USER_ID']; ?></td>
                <td><img src='../profile_image/<?php echo $user['USER_PROFILE_PICTURE']; ?>' alt='Product Image' style='width:50px;height:50px;'></td>
                <td><?php echo $user['FIRST_NAME'] . ' ' . $user['LAST_NAME']; ?></td>
                <td><?php echo $user['USER_ADDRESS']; ?></td>
                <td><?php echo $user['USER_EMAIL']; ?></td>
                <td><?php echo $user['USER_CONTACT_NO']; ?></td>
                <td><?php echo $user['USER_AGE']; ?></td>
                <td><?php echo $user['USER_GENDER']; ?></td>
                <td>
                    <a href="admin_view_customer.php?id=<?php echo $user['USER_ID']; ?>&action=edit">View</a> | 
                    <a href="admin_delete_customer.php?id=<?php echo $user['USER_ID']; ?>&action=delete">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
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