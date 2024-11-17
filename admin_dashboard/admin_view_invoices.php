<?php
 include("admin_session.php");
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Invoice Details</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_products.css">
    <link rel="stylesheet" href="admin_view_invoices.css">
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
        <div id="orderFormContainer" class="order-form-container">
        <img src="../logo.png" alt="Company Logo" class="logo_detail">
        <h2 class="form-heading">HudderFoods <br> Invoice</h2>
        <form id="orderForm" class="order-form">
    <div class="form-row">
        <div class="form-column">
            <label for="orderId" class="form-label">Invoice Number:</label>
            <input type="text" id="orderId" name="orderId" class="form-input" placeholder="Enter order ID">
        </div>
        <div class="form-column">
            <label for="customerId" class="form-label">Customer Name:</label>
            <input type="text" id="customerId" name="customerId" class="form-input" placeholder="Enter customer ID">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="orderDate" class="form-label">Invoice Date:</label>
            <input type="date" id="orderDate" name="orderDate" class="form-input">
        </div>
        <div class="form-column">
            <label for="pickupDate" class="form-label">Customer Address:</label>
            <input type="text" id="pickupDate" name="pickupDate" class="form-input" placeholder="Customer Address">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="pickupLocation" class="form-label">Order Id:</label>
            <input type="text" id="pickupLocation" name="pickupLocation" class="form-input" placeholder="Enter your order id">
        </div>
        <div class="form-column">
        <div class="form-column">
            <label for="pickupLocation" class="form-label">Contact Number:</label>
            <input type="text" id="pickupLocation" name="pickupLocation" class="form-input" placeholder="Enter your Contact Number">
        </div>
        </div>
    </div>
</form>
    </div>
    <div id="productTableContainer" class="product-table-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Picture</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><img src="../caviber_image.jpg" alt="Product Image" class="product-image"></td>
                <td>Product Name 1</td>
                <td>2</td>
                <td>$10</td>
                <td>$20</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>
<div id="paymentFormContainer" class="payment-form-container">
<form id="paymentForm" class="payment-form">
    <div class="form-row">
        <div class="form-column">
            <label for="netTotal" class="form-label">Net Total:</label>
            <input type="text" id="netTotal" name="netTotal" class="form-input" placeholder="Enter net total">
        </div>
        <div class="form-column">
            <label for="discountPercent" class="form-label">Discount Percent:</label>
            <input type="text" id="discountPercent" name="discountPercent" class="form-input" placeholder="Enter discount percent">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="discountAmount" class="form-label">Discount Amount:</label>
            <input type="text" id="discountAmount" name="discountAmount" class="form-input" placeholder="Enter discount amount">
        </div>
        <div class="form-column">
            <label for="totalAmount" class="form-label">Total Amount:</label>
            <input type="text" id="totalAmount" name="totalAmount" class="form-input" placeholder="Enter total amount">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="paymentMode" class="form-label">Payment Mode:</label>
            <input type="text" id="paymentMode" name="paymentMode" class="form-input" placeholder="Enter payment mode">
        </div>
        <div class="form-column">
            <label for="paymentStatus" class="form-label">Payment Status:</label>
            <input type="text" id="paymentStatus" name="paymentStatus" class="form-input" placeholder="Enter payment status">
        </div>
    </div>
</form>
</div>
<div id="returnToOrdersContainer" class="return-to-orders-container">
    <button onclick="window.location.href='admin_invoice.php'" class="return-to-orders-btn">Return to Invoices</button>
</div>
<script src="admin_navbar.js"></script>
</body>
</html>