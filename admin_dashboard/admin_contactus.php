<?php
 include("admin_session.php");
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../connection/connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Query</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_product.css">
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
    <h1 class="page-title">Contact Us Details</h1>
    

    <div class="user-details-container">
        <table border=1 id="myTable">
            <thead>
                <tr>
                    <th>Query  ID</th>
                    <th>Name</th>
                    <th>EMAIL</th>
                    <th>CONTACT NO</th>
                    <th>SUBJECT</th> 
                    <th>MESSAGE</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include("../connection/connection.php");
                    $sql = "SELECT * FROM CONTACTUS WHERE VERIFIED_STATUS = 1";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);

                    while ($row = oci_fetch_assoc($stmt)) {
                        echo "<tr>";
                        echo "<td>" . $row['QUERY_ID'] . " </td>";
                        echo "<td>" . $row['FIRST_NAME'] . " ". $row['LAST_NAME'] . " </td>";
                        echo "<td>" . $row['EMAIL'] . " </td>";
                        echo "<td>" . $row['CONTACT_NO'] . " </td>";
                        echo "<td>" . $row['SUBJECT'] . " </td>";
                        echo "<td>" . $row['MESSAGE'] . " </td>";
                        echo "<td>  <a href='deletequery.php?id=" . $row['QUERY_ID'] . "&action=delete'> Delete </a> </td>";
                        echo "</tr>";
                    }
                    oci_free_statement($stmt);
                    oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <script src="admin_product.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable', {
            responsive: true,
        });
        window.onload = function() {
            var sortSelect = document.getElementById("sort");
            var selectedValue = localStorage.getItem("selectedSortValue");
            if (selectedValue) {
                sortSelect.value = selectedValue;
            }
        };

        function submitForm() {
            var sortSelect = document.getElementById("sort");
            localStorage.setItem("selectedSortValue", sortSelect.value);
            document.getElementById("sortForm").submit();
            // Reload the page
            location.reload();
        }
    </script>
</body>
</html>