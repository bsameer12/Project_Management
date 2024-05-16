<?php
// Set the variable $userId based on $_SESSION["userid"]
session_start();
$userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : 0;
include("connection/connection.php");
    $Search_text = $_GET["value"];
            include("connection/connection.php");
            $Search_text = $_GET["value"];
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
                    // Define filter and sort variables
                    $min_price = isset($_POST["min-price"]) ? sanitizeInteger($_POST["min-price"]) : null;
                    $max_price = isset($_POST["max-price"]) ? sanitizeInteger($_POST["max-price"]) : null;
                    $category1 = isset($_POST["category1"]) ? sanitizeInteger($_POST["category1"]) : null;
                    $category2 = isset($_POST["category2"]) ? sanitizeInteger($_POST["category2"]) : null;
                    $category3 = isset($_POST["category3"]) ? sanitizeInteger($_POST["category3"]) : null;
                    //$rating = isset($_POST["rating"]) ? sanitizeInteger($_POST["rating"]) : null;
                    $sort_by = isset($_POST["sort-by"]) ? sanitizeString($_POST["sort-by"]) : null;
            
                // Prepare the base SQL statement
                $sql = "SELECT PRODUCT_ID, PRODUCT_NAME, PRODUCT_PRICE, PRODUCT_QUANTITY, IS_DISABLED, PRODUCT_PICTURE, CATEGORY_ID 
                        FROM product 
                        WHERE PRODUCT_NAME LIKE '%' || :search_text || '%'";
            
                // Add filter conditions
                if ($min_price !== null && $max_price !== null) {
                    $sql .= " AND PRODUCT_PRICE BETWEEN :min_price AND :max_price";
                }
                if ($category1 !== null || $category2 !== null || $category3 !== null) {
                    $sql .= " AND CATEGORY_ID IN (:category1, :category2, :category3)";
                }
                /*if ($rating !== null) {
                    $sql .= " AND RATING >= :rating";
                }
                */
            
                // Add sorting condition
                switch ($sort_by) {
                    case "alphabetically_asc":
                        $sql .= " ORDER BY PRODUCT_NAME ASC";
                        break;
                    case "alphabetically_desc":
                        $sql .= " ORDER BY PRODUCT_NAME DESC";
                        break;
                    case "price-low-to-high":
                        $sql .= " ORDER BY PRODUCT_PRICE ASC";
                        break;
                    case "price-high-to-low":
                        $sql .= " ORDER BY PRODUCT_PRICE DESC";
                        break;
                    /*
                    case "rating-high-to-low":
                        $sql .= " ORDER BY RATING DESC";
                        break;
                    case "rating-low-to-high":
                        $sql .= " ORDER BY RATING ASC";
                        break;
                        */
                    default:
                        // Default sorting if no valid option selected
                        $sql .= " ORDER BY PRODUCT_ID DESC";
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
                if ($category1 !== null || $category2 !== null || $category3 !== null) {
                    oci_bind_by_name($stmt, ':category1', $category1);
                    oci_bind_by_name($stmt, ':category2', $category2);
                    oci_bind_by_name($stmt, ':category3', $category3);
                }
                /*
                if ($rating !== null) {
                    oci_bind_by_name($stmt, ':rating', $rating);
                }
                */
            
                // Execute the SQL statement
                oci_execute($stmt);

                // Count the number of rows fetched
                $numRows = 0;

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
                <option value="0">0</option>
                <option value="10">$10</option>
                <option value="20">$20</option>
                <option value="30">$30</option>
                <!-- Add more options as needed -->
            </select>
            <label for="max-price">Max:</label>
            <select name="max-price" id="max-price">
                <option value="50">$50</option>
                <option value="100">$100</option>
                <option value="200">$200</option>
                <option value="500">$500</option>
                <!-- Add more options as needed -->
            </select>
        
            <h3>Category</h3>
            <label for="category1"> <input type="checkbox" id="category1" name="category1" value="1"> Category 1</label><br>
            <label for="category2"> <input type="checkbox" id="category2" name="category2" value="2"> Category 2</label><br>
            <label for="category3"> <input type="checkbox" id="category3" name="category3" value="3"> Category 3</label><br>
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

                    echo "<div class='product-card'>";
                    echo "<div class='product-details'>";
                    echo "<div class='product-image'>";
                        echo "<img src='product_image/" . $row['PRODUCT_PICTURE'] ."' alt='Product Image'>";
                    echo"</div>";
                        echo "<p class='product-name'>" .$row['PRODUCT_NAME'] ."</p>";
                        echo"<div class='product-rating'>";
                            echo"<span class='stars'>&#9733;&#9733;&#9733;&#9733;&#9734;</span>";
                            echo"<span class='total-reviews'>(15)</span>";
                        echo"</div>";
                        echo"<div id='price_container'>";
                            echo"<div id='original_price'>". $row['PRODUCT_PRICE'] . "</div>";
                            echo"<div id='discount>-20%</div>";
                            echo"<div id='discount_price'>" . $row['PRODUCT_PRICE'] . "</div>";
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
</script>

</body>
</html>