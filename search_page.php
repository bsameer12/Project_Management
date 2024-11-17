<?php
session_start();
$userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : 0;
include("connection/connection.php");
$Search_text = isset($_GET["value"]) ? $_GET["value"] : '';



// Define an array to store the category data
$categoryArray = [];

// Query to select CATEGORY_ID and CATEGORY_TYPE from PRODUCT_CATEGORY
$sql = "SELECT CATEGORY_ID, CATEGORY_TYPE FROM PRODUCT_CATEGORY";

// Execute the query
$result = oci_parse($conn, $sql);
oci_execute($result);

// Fetch the rows and store them in the category array
while ($row = oci_fetch_assoc($result)) {
    $categoryArray[] = $row;
}

// Free the statement resources
oci_free_statement($result);

// Function to sanitize integer input
function sanitizeInteger($input) {
    // Remove non-numeric characters except '-' sign
    $sanitized_input = preg_replace("/[^0-9\-]/", "", $input);
    // Convert to integer
    $sanitized_input = (int)$sanitized_input;
    return $sanitized_input;
}

// Function to sanitize string input
function sanitizeString($input) {
    // Remove HTML tags and special characters
    $sanitized_input = htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    return $sanitized_input;
}



// Define the $category variable
$category = null;
// Define filter and sort variables
$min_price = isset($_POST["min-price"]) ? sanitizeInteger($_POST["min-price"]) : null;
$max_price = isset($_POST["max-price"]) ? sanitizeInteger($_POST["max-price"]) : null;
// Check if $_POST["category"] is set and sanitize it
if (isset($_POST["category"])) {
    // Set $category to the sanitized value if $_POST["category"] is set, otherwise set it to null
    $category = sanitizeInteger($_POST["category"]);
} elseif (isset($_GET["category_id"])) { // If not, check if $_GET["category_id"] is set and sanitize it
    // Set $category to the sanitized value of $_GET["category_id"]
    $category = sanitizeInteger($_GET["category_id"]);
}

$sort_by = isset($_POST["sort-by"]) ? sanitizeString($_POST["sort-by"]) : null;

// Prepare the base SQL statement
$sql = "SELECT 
p.PRODUCT_ID, 
p.PRODUCT_NAME, 
p.PRODUCT_PRICE, 
p.PRODUCT_PICTURE, 
p.PRODUCT_QUANTITY,
AVG(r.REVIEW_SCORE) OVER() AS AVG_REVIEW_SCORE,
COUNT(r.REVIEW_SCORE) OVER() AS TOTAL_REVIEWS,
COALESCE(d.DISCOUNT_PERCENT, '') AS DISCOUNT_PERCENT
FROM 
product p
LEFT JOIN 
review r ON p.PRODUCT_ID = r.PRODUCT_ID
LEFT JOIN 
discount d ON p.PRODUCT_ID = d.PRODUCT_ID
WHERE 
p.IS_DISABLED = 1 
AND ADMIN_VERIFIED = 1
AND p.PRODUCT_NAME LIKE '%' || :search_text || '%'";

// Add filter conditions
if ($min_price !== null && $max_price !== null) {
    $sql .= " AND p.PRODUCT_PRICE BETWEEN :min_price AND :max_price";
}
if ($category !== null) {
    $sql .= " AND p.CATEGORY_ID = :category";
}

// Add sorting condition
switch ($sort_by) {
    case "alphabetically_asc":
        $sql .= " ORDER BY p.PRODUCT_NAME ASC";
        break;
    case "alphabetically_desc":
        $sql .= " ORDER BY p.PRODUCT_NAME DESC";
        break;
    case "price-low-to-high":
        $sql .= " ORDER BY p.PRODUCT_PRICE ASC";
        break;
    case "price-high-to-low":
        $sql .= " ORDER BY p.PRODUCT_PRICE DESC";
        break;
    default:
        // Default sorting if no valid option selected
        $sql .= " ORDER BY p.PRODUCT_ID DESC";
        break;
}

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind parameters
oci_bind_by_name($stmt, ':search_text', $Search_text);
if ($min_price !== null && $max_price !== null) {
    oci_bind_by_name($stmt, ':min_price', $min_price);
    oci_bind_by_name($stmt, ':max_price', $max_price);
}
if ($category !== null) {
    oci_bind_by_name($stmt, ':category', $category);
}

// Execute the SQL statement
oci_execute($stmt);

// Count the number of rows fetched
$numRows = 0;

// Initialize an array to store the fetched rows
$fetchedRows = [];

// Fetch the rows and count them
while ($row = oci_fetch_assoc($stmt)) {
    // Increment the row count
    $numRows++;
    
    // Store the fetched row
    $fetchedRows[] = $row;
}


            
               
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="search_page.css">
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
    <div class="container_search">
        <div class="left-sidebar">
            <h2>Filter</h2>
            <form class="filter-form" id="price-filter" name="price-filter" action="" method="POST">
            <h3>Price</h3>
            <label for="min-price">Min:</label>
            <select name="min-price" id="min-price">
                <option value="0">€0</option>
                <option value="10">€10</option>
                <option value="20">€20</option>
                <option value="30">€30</option>
                <!-- Add more options as needed -->
            </select>
            <label for="max-price">Max:</label>
            <select name="max-price" id="max-price">
                <option value="50">€50</option>
                <option value="100">€100</option>
                <option value="200">€200</option>
                <option value="500">€500</option>
                <!-- Add more options as needed -->
            </select>
        
            <h3>Category</h3>
            <?php
            foreach ($categoryArray as $category) {
    $categoryId = $category['CATEGORY_ID'];
    $categoryType = $category['CATEGORY_TYPE'];
    echo "<label for='category$categoryId'> <input type='checkbox' id='category$categoryId' name='category' value='$categoryId'> $categoryType</label><br>";
    
}
?>
            <!-- Add more checkbox inputs for categories as needed -->
                <h3>Rating</h3>
                <label for="rating">Rating:</label>
                <select name="rating" id="rating">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </form>
        </div>

        <div class="right-content">
            <div class="top-section">
                <p><bold> Showing <?php echo $numRows ?> Products </bold> </p>
                <form class="sort-form" name="sort_form" id="sort_form" method="POST" action="">
                    <label for="sort-by">Sort By: <select name="sort-by" id="sort-by">
                        <option value="alphabetically_asc">Name: A to Z</option>
                        <option value="alphabetically_desc">Name: Z to A</option>
                        <option value="price-low-to-high">Price: Low to High</option>
                        <option value="price-high-to-low">Price: High to Low</option>
                        <option value="rating-high-to-low">Rating: High to Low</option>
                        <option value="rating-low-to-high">Rating: Low to High</option>
                        <!-- Add more sorting options as needed -->
                    </select></label>
                </form>
            </div>
            <div class="bottom-section">
                <!-- Container for other content -->
                <div class="product-card-container">
                    <?php
                     // Fetch the rows
                     foreach ($fetchedRows as $row):
                    // Access data from each row

                    echo "<div class='product-card' onclick='redirectToProductPage(" . $row['PRODUCT_ID'] . ")'>";
                    echo "<div class='product-details'>";
                    echo "<div class='product-image'>";
                        echo "<img src='product_image/" . $row['PRODUCT_PICTURE'] ."' alt='Product Image'>";
                    echo"</div>";
                        echo "<p class='product-name'>" .$row['PRODUCT_NAME'] ."</p>";
                        echo"<div class='product-rating'>";
                        echo "<span class='stars'>";
                        
                            $rating = round($row['AVG_REVIEW_SCORE']);
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $rating) {
                                    echo '&#9733;';
                                } 
                            }
                        echo "</span>";
                        echo "<span class='total-reviews'>( " . $row['TOTAL_REVIEWS'] . ")</span>";
                        echo"</div>";
                        echo"<div id='price_container'>";
                            echo"<div id='original_price'>€". $row['PRODUCT_PRICE'] . "</div>";
                            $original_price = $row['PRODUCT_PRICE'];
                            $discount_percent = $row['DISCOUNT_PERCENT'];
                            $discount_amount = ($original_price * $discount_percent) / 100;
                            $discount_price = $original_price - $discount_amount;
                            echo "<div id='discount'>-" . ($row['DISCOUNT_PERCENT'] ?? '0') . "%</div>";
                            echo"<div id='discount_price'>€" . $discount_price . "</div>";
                        echo "</div>";
                        echo"<div class='button-container'>";
                        if ($row['PRODUCT_QUANTITY'] <= 0) {
                            // If the product quantity is 0 or less, disable the button
                            echo "<button class='add-to-cart-btn' disabled>Add to Cart</button>";
                        } else {
                            // Otherwise, render the button with the onclick event
                            echo "<button class='add-to-cart-btn' onclick='addToCart(" . $row['PRODUCT_ID'] . "," . $userId . ", \"" . $Search_text . "\")'>Add to Cart</button>";
                        }                        
                            echo"<a href='add_to_wishlist.php?produt_id=" . $row['PRODUCT_ID'] . "&user_id=" . $userId ."&searchtext=" . $Search_text ."' class='wishlist-btn'><i class='fas fa-heart'></i></a>";
                        echo"</div>";
                    echo"</div>";
                echo"</div>";
            endforeach;
            
                // Free statement resources
                oci_free_statement($stmt);
                ?>
                
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"> </script>
    <script>
    // Get the form element
    const form = document.getElementById('price-filter');

    // Restore form values from localStorage on page load
    window.addEventListener('load', function() {
        const formData = JSON.parse(localStorage.getItem('formData')) || {};
        for (const key in formData) {
            if (formData.hasOwnProperty(key)) {
                const element = document.querySelector(`[name="${key}"]`);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = formData[key];
                    } else {
                        element.value = formData[key];
                    }
                }
            }
        }
    });

    // Add event listeners to form inputs
    form.addEventListener('change', function() {
        // Store form values in localStorage
        const formData = {};
        const elements = form.elements;
        for (let i = 0; i < elements.length; i++) {
            const element = elements[i];
            if (element.type !== 'submit') {
                formData[element.name] = element.type === 'checkbox' ? element.checked : element.value;
            }
        }
        localStorage.setItem('formData', JSON.stringify(formData));

        // Submit the form when any input changes
        form.submit();
    });

    // Get the sort form element
    const sortForm = document.getElementById('sort_form');

    // Restore last selected input after form submission
    window.addEventListener('load', function() {
        const lastSelectedOption = localStorage.getItem('lastSelectedOption');
        if (lastSelectedOption) {
            document.getElementById('sort-by').value = lastSelectedOption;
        }
    });

    // Add event listener to submit sort form on change
    document.getElementById('sort-by').addEventListener('change', function() {
        // Store the last selected option in localStorage
        localStorage.setItem('lastSelectedOption', this.value);
        // Submit the sort form
        sortForm.submit();
    });

    // Reload the page after form submission
    form.addEventListener('submit', function() {
        window.location.reload();
    });

    function addToCart(productId, userId, searchText) {
        // Redirect to add_to_cart.php with the productId, userId, and searchText parameters
        window.location.href = 'add_to_cart.php?productid=' + productId + '&userid=' + userId + '&searchtext=' + searchText;
    }

    function redirectToProductPage(productId) {
        // Redirect to the product page with the specific product ID
        window.location.href = "product.php?productId=" + productId;
    }
    
</script>

</body>
</html>