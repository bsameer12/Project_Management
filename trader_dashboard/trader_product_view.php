<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_product_view.css">
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
    <div class="container-heading">
        <h2 class="container-heading">Product  Details</h2>
        </div>
        <div id="productDetailsContainer" class="product-details-container">
    <div class="left-div">
        <img src="../chese_image.jpg" alt="Product Picture" class="product-picture">
    </div>
    <div class="right-div">
        <form id="productDetailsForm" class="product-details-form">
            <div class="form-row">
                <label for="productId" class="form-label">Product ID:</label>
                <input type="text" id="productId" name="productId" class="form-input" placeholder="Enter product ID">
            </div>
            <div class="form-row">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" id="productName" name="productName" class="form-input" placeholder="Enter product name">
            </div>
            <div class="form-row">
                <label for="productCategory" class="form-label">Product Category:</label>
                <select id="productCategory" name="productCategory" class="form-input">
                    <option value="category1">Category 1</option>
                    <option value="category2">Category 2</option>
                    <option value="category3">Category 3</option>
                </select>
            </div>
            <div class="form-row">
                <label for="productPrice" class="form-label">Product Price:</label>
                <input type="text" id="productPrice" name="productPrice" class="form-input" placeholder="Enter product price">
            </div>
            <div class="form-row">
                <label for="productDescription" class="form-label">Product Description:</label>
                <textarea id="productDescription" name="productDescription" class="form-textarea" rows="4" placeholder="Enter product description"></textarea>
            </div>
            <div class="form-row">
                <label for="stockQuantity" class="form-label">Stock Quantity:</label>
                <input type="text" id="stockQuantity" name="stockQuantity" class="form-input" placeholder="Enter stock quantity">
            </div>
            <div class="form-row">
                <label for="quantityStatus" class="form-label">Quantity Status:</label>
                <input type="text" id="quantityStatus" name="quantityStatus" class="form-input" placeholder="Enter quantity status">
            </div>
            <div class="form-row">
                <input type="submit" id="saveChangesBtn" class="submit-btn" value="Save Changes">
                <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_products.php' ; return false;">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script src="trader_navbar.js"></script>
</body>
</html>