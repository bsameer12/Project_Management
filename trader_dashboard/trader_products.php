<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_products.css">
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
        include("trader_navbar.php");
    ?>
    <h1 class="page-title">Product Details</h1>
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
        <div class="search-container">
            <button class="create-new-btn" onclick="openForm()">Add Product</button>
        </div>
    </div>
    <div class="form-popup" id="productForm">
    <div class="form-container">
        <h2>Product Registration Form</h2>
        <span class="close" onclick="closeForm()">&times;</span>
        <div class="profile-circle" id="productImagePreview"></div>
        <form id="productForms" name="productForms" action="" enctype="multipart/form-data" method="POST">
            <!-- Add fields for product details -->
            <div class="row">
                <div class="col">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="productName" required>
                </div>
                <div class="col">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="Pizza">Pizza</option>
                        <option value="Momo">Momo</option>
                        <option value="Drinks">Drinks</option>
                        <option value="Tea">Tea</option>
                        <option value="Coffee">Coffee</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>
                <!-- Add more fields as needed -->
            </div>
            <div class="row">
                <div class="col">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div class="col">
                    <label for="productImage">Upload Product Image:</label>
                    <input type="file" id="productImage" name="productImage" accept="image/*" onchange="previewProductImage()" required>
                </div>
            </div>
                <input type="submit" id="submit_product" name="submit_product" value="Add Product" class="form-buttons" style="background-color: #4CAF50; color: white; text-align: center; ">
        </form>
    </div>
</div>
</div>
</div>


    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> ID </th> 
                    <th> Image </th>
                    <th> Product Name </th>
                    <th> Category </th>
                    <th> Price </th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        
            <tr>
            <td> 1001 </td>
            <td><img src='../caviber_image.jpg' alt='Product Image' style='width:50px;height:50px;'></td>
            <td> Hello</td>
            <td>fghhjffg</td>
            <td>10000</td>
            <td> <a href=admin_edit_poroduct.php?id=$id&action=edit> Edit </a> | <a href=deleteproduct.php?id=$id&action=delete> Delete </a> </td>
            </tr>
        </tbody>
        </table>
    </div>
    <script src="trader_product.js"></script>
    <script src="trader_navbar.js"></script>
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