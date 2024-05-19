<?php
include("../connection/connection.php");

// SQL query to select the required fields
$sql = "SELECT S.SHOP_ID, S.SHOP_NAME, S.SHOP_DESCRIPTION, S.REGISTRATION_NO, S.SHOP_CATEGORY_ID, S.SHOP_PROFILE, S.VERIFIED_SHOP,
               H.FIRST_NAME, H.LAST_NAME AS NAME
        FROM SHOP S
        JOIN HUDDER_USER H ON S.USER_ID = H.USER_ID";

// Prepare the OCI statement
$stmt = oci_parse($conn, $sql);

// Execute the statement
if (oci_execute($stmt)) {
    // Array to hold the results
    $results = [];

    // Fetch all the results
    while ($row = oci_fetch_assoc($stmt)) {
        $results[] = [
            'SHOP_ID' => $row["SHOP_ID"],
            'SHOP_NAME' => $row['SHOP_NAME'],
            'SHOP_DESCRIPTION' => $row['SHOP_DESCRIPTION'],
            'REGISTRATION_NO' => $row['REGISTRATION_NO'],
            'SHOP_CATEGORY_ID' => $row['SHOP_CATEGORY_ID'],
            'SHOP_PROFILE' => $row['SHOP_PROFILE'],
            'VERIFIED_SHOP' => $row['VERIFIED_SHOP'],
            'FIRST_NAME' => $row['FIRST_NAME'],
            'NAME' => $row['NAME']
        ];
    }

} else {
    // Handle SQL execution error
    $error = oci_error($stmt);
    echo "Error executing SQL statement: " . $error['message'];
}

// Free the statement and close the connection
oci_free_statement($stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Details</title>
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
    <h1 class="page-title">Shop Details</h1>
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Shop ID </th> 
                    <th> Shop Profile </th>
                    <th> Shop Name</th>
                    <th> Shop Description </th>
                    <th> Shop Regritation Number </th>
                    <th> Shop Category</th>
                    <th> Shop Owner </th>
                    <th> Shop Status </th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($results as $index => $shop) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($shop['SHOP_ID']) . "</td>"; // Assuming Shop ID is auto-increment or use $shop['SHOP_ID'] if exists
                echo "<td><img src='../shop_profile_image/" . $shop['SHOP_PROFILE'] . "' alt='Shop Profile' width='50' height='50'></td>";
                echo "<td>" . htmlspecialchars($shop['SHOP_NAME']) . "</td>";
                echo "<td>" . htmlspecialchars($shop['SHOP_DESCRIPTION']) . "</td>";
                echo "<td>" . htmlspecialchars($shop['REGISTRATION_NO']) . "</td>";
                echo "<td>" . htmlspecialchars($shop['SHOP_CATEGORY_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($shop['FIRST_NAME'] . " " . $shop['NAME']) . "</td>";
                echo "<td>" . ($shop['VERIFIED_SHOP'] ? 'Verified' : 'Not Verified') . "</td>";
                echo "<td>
                        <a href='admin_view_shop_detail.php?id=" .  htmlspecialchars($shop['SHOP_ID']) . "&action=view'>View</a> |
                        <a href='admin_view_shop_detail.php?id=" .  htmlspecialchars($shop['SHOP_ID']) . "&action=delete'>Delete</a>
                      </td>";
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