<?php
include("connection/connection.php");
// Define an array to store the category data
$categoryArray = [];

// Query to select CATEGORY_ID and CATEGORY_TYPE from PRODUCT_CATEGORY
$sql = "SELECT CATEGORY_ID, CATEGORY_TYPE, CATEGORY_IMAGE FROM PRODUCT_CATEGORY";

// Execute the query
$result = oci_parse($conn, $sql);
oci_execute($result);

// Fetch the rows and store them in the category array
while ($row = oci_fetch_assoc($result)) {
    $categoryArray[] = $row;
}

// Free the statement resources
oci_free_statement($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="category.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
</head>
<body>
    <?php
         require("navbar_switching.php");
         includeNavbarBasedOnSession();
    ?>
    <section class="category-section" id="category-section">
    <h2>Categories</h2>
    <div class="category-container">
            <?php foreach ($categoryArray as $category): ?>
        <div class="category-item">
            <a href="search_page?category_id=<?php echo $category['CATEGORY_ID']; ?>"><img src="category_picture/<?php echo $category['CATEGORY_IMAGE']; ?>" alt="<?php echo $category['CATEGORY_TYPE']; ?>"></a>
            <p><?php echo $category['CATEGORY_TYPE']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <script src="faqs.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>