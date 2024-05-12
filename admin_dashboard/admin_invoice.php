<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices</title>
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
    <h1 class="page-title">Invoice Details</h1>
    <div class="product-container">
    <div class="sort-container">
            <form id="sortForm">
                <label for="sort">Sort:</label>
                <select id="sort" onchange="submitForm()">
                    <option value="new_to_old">New to Old</option>
                    <option value="old_to_new">Old to New</option>
                    <option value="alpha_asc">Alphabetically Increasing</option>
                    <option value="alpha_desc">Alphabetically Decreasing</option>
                    <option value="price_high_low">Price High to Low</option>
                    <option value="price_low_high">Price Low to High</option>
                </select>
            </form>
        </div>
    </div>
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Order ID </th> 
                    <th> Total Amount </th>
                    <th> Order Date</th>
                    <th> Customer Id </th>
                    <th> Pick Up Date </th>
                    <th> Status</th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        
            <tr>
            <td> 1001 </td>
            <td>15000</td>
            <td> 2024-04-28</td>
            <td>1223</td>
            <td>2025-03-04</td>
            <td>Ready to be delivered!!!</td>
            <td> <a href=admin_view_invoices.php?id=$id&action=edit> View </a></td>
            </tr>
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
    </script>
</body>
</html>