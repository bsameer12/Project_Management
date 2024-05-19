<?php
include("../connection/connection.php");


// SQL query to fetch review and user details
$sql = "
SELECT 
    R.REVIEW_ID, 
    R.REVIEW_DATE, 
    R.REVIEW_SCORE, 
    R.FEEDBACK, 
    R.PRODUCT_ID, 
    U.FIRST_NAME || ' ' || U.LAST_NAME AS NAME, 
    U.USER_PROFILE_PICTURE,
    P.PRODUCT_NAME
FROM 
    REVIEW R
JOIN 
    HUDDER_USER U ON R.USER_ID = U.USER_ID
JOIN 
    PRODUCT P ON R.PRODUCT_ID = P.PRODUCT_ID
WHERE 
    R.REVIEW_PROCIDED = 1
";
// Prepare and execute the statement
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

// Fetch all results into an array
$reviews = [];
while ($row = oci_fetch_assoc($stmt)) {
    $reviews[] = $row;
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
    <title>Review And Ratings</title>
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
    <h1 class="page-title">Reviews</h1>
    
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Review ID </th> 
                    <th> User Profile </th>
                    <th> User Name </th>
                    <th> User Rating </th>
                    <th> User Review </th>
                    <th> Review Date </th>
                    <th> Revied Product</th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['REVIEW_ID']); ?></td>
                    <td><img src="../profile_image/<?php echo htmlspecialchars($review['USER_PROFILE_PICTURE']); ?>" alt="User Profile" style='width:50px;height:50px;'></td>
                    <td><?php echo htmlspecialchars($review['NAME']); ?></td>
                    <td><?php echo htmlspecialchars($review['REVIEW_SCORE']); ?></td>
                    <td><?php echo htmlspecialchars($review['FEEDBACK']); ?></td>
                    <td><?php echo htmlspecialchars($review['REVIEW_DATE']); ?></td>
                    <td><?php echo htmlspecialchars($review['PRODUCT_NAME']); ?></td>
                    <td><a href="admin_delete_review.php?id=<?php echo htmlspecialchars($review['REVIEW_ID']); ?>&action=delete">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <script src="admin_product.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    </script>
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
    }
    </script>
</body>
</html>