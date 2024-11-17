<?php
 include("admin_session.php");
// Include the database connection file
include("../connection/connection.php");

// Define the SQL query to select USER_ID and SHOP_NAME where USER_TYPE is 'trader'
$sql = "
    SELECT HU.USER_ID, S.SHOP_NAME
    FROM HUDDER_USER HU
    JOIN SHOP S ON HU.USER_ID = S.USER_ID
    WHERE HU.USER_TYPE = 'trader'
";

// Parse the SQL query
$stmt = oci_parse($conn, $sql);

// Execute the SQL query
oci_execute($stmt);

// Initialize an array to store the results
$user_shop_details = array();

// Fetch the results and store them in the array
while ($row = oci_fetch_assoc($stmt)) {
    $user_shop_details[] = $row;
}

// Free the statement resource
oci_free_statement($stmt);


// Get the selected trader and time period from POST request
$selected_trader = $_POST['trader'] ?? 'all';
$selected_time_period = $_POST['time_period'] ?? '1';

// Calculate the start date based on the selected time period
$time_period_days = (int)$selected_time_period;
$start_date = date('Y-m-d', strtotime("-$time_period_days days"));

// Define the SQL query to select ORDER_TIME
$sql_order_time = "
    SELECT DISTINCT TO_CHAR(OP.ORDER_TIME, 'YYYY-MM-DD\"T\"HH24:MI:SS') AS ORDER_TIME
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

if ($selected_trader !== 'all') {
    $sql_order_time .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_order_time = oci_parse($conn, $sql_order_time);

// Bind the parameters
oci_bind_by_name($stmt_order_time, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_order_time, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_order_time);

// Initialize an array to store the results
$order_times = array();

// Fetch the results and store them in the array
while ($row = oci_fetch_assoc($stmt_order_time)) {
    $order_times[] = $row['ORDER_TIME'];
}

// Free the statement resource
oci_free_statement($stmt_order_time);


// Define the SQL query to select distinct PRODUCT_ID and sum PRODUCT_QTY, and select PRODUCT_NAME
$sql_top_products = "
    SELECT *
    FROM (
        SELECT
            PD.PRODUCT_ID,
            PD.PRODUCT_NAME,
            SUM(OD.PRODUCT_QTY) AS TOTAL_QTY
        FROM
            ORDER_DETAILS OD
        JOIN
            PRODUCT PD
        ON
            OD.PRODUCT_ID = PD.PRODUCT_ID
        JOIN
            ORDER_PRODUCT OP
        ON
            OD.ORDER_PRODUCT_ID = OP.ORDER_PRODUCT_ID
        WHERE
            OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

if ($selected_trader !== 'all') {
    $sql_top_products .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

$sql_top_products .= "
        GROUP BY
            PD.PRODUCT_ID,
            PD.PRODUCT_NAME
        ORDER BY
            TOTAL_QTY DESC
    )
WHERE ROWNUM <= 5";



// Parse the SQL query
$stmt_top_products = oci_parse($conn, $sql_top_products);

// Bind the parameters
oci_bind_by_name($stmt_top_products, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_top_products, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_top_products);

// Initialize an array to store the top 5 products
$top_products = array();

// Fetch the results and store them in the array
while ($row = oci_fetch_assoc($stmt_top_products)) {
    $top_products[] = $row;
}

// Free the statement resource
oci_free_statement($stmt_top_products);

// Initialize arrays to store product names and quantities
$productNames = array();
$productQuantities = array();

// Iterate over the top_products array to extract product names and quantities
foreach ($top_products as $product) {
    // Extract product name and total quantity sold
    $productName = $product['PRODUCT_NAME'];
    $totalQty = (int)$product['TOTAL_QTY']; // Convert to integer for consistency

    // Append product name and quantity to respective arrays
    $productNames[] = $productName;
    $productQuantities[] = $totalQty;
}



// Define the SQL query to sum PRODUCT_QTY from ORDER_DETAILS
$sql_sum_product_qty = "
    SELECT SUM(OD.PRODUCT_QTY) AS TOTAL_QTY
    FROM ORDER_DETAILS OD
    JOIN ORDER_PRODUCT OP ON OD.ORDER_PRODUCT_ID = OP.ORDER_PRODUCT_ID
    WHERE OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
    AND OP.ORDER_STATUS > 0
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_product_qty .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_sum_product_qty = oci_parse($conn, $sql_sum_product_qty);

// Bind the parameters
oci_bind_by_name($stmt_sum_product_qty, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_product_qty, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_product_qty);

// Fetch the result
$total_qty_result = oci_fetch_assoc($stmt_sum_product_qty);

// Get the total quantity
$total_qty = (int)$total_qty_result['TOTAL_QTY'];

// Free the statement resource
oci_free_statement($stmt_sum_product_qty);


// Define the SQL query to sum TOTAL_PRICE from ORDER_PRODUCT
$sql_sum_total_price = "
    SELECT SUM(OP.TOTAL_PRICE) AS TOTAL_PRICE_SUM
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
    AND OP.ORDER_STATUS > 0
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_total_price .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_sum_total_price = oci_parse($conn, $sql_sum_total_price);

// Bind the parameters
oci_bind_by_name($stmt_sum_total_price, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_total_price, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_total_price);

// Fetch the result
$total_price_result = oci_fetch_assoc($stmt_sum_total_price);

// Get the total price sum
$total_price_sum = (float)$total_price_result['TOTAL_PRICE_SUM'];

// Free the statement resource
oci_free_statement($stmt_sum_total_price);



// Define the SQL query to count CUSTOMER_ID from ORDER_PRODUCT
$sql_count_customer_id = "
    SELECT COUNT(DISTINCT OP.CUSTOMER_ID) AS CUSTOMER_COUNT
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
    AND OP.ORDER_STATUS > 0
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_count_customer_id .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_count_customer_id = oci_parse($conn, $sql_count_customer_id);

// Bind the parameters
oci_bind_by_name($stmt_count_customer_id, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_count_customer_id, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_count_customer_id);

// Fetch the result
$customer_count_result = oci_fetch_assoc($stmt_count_customer_id);

// Get the customer count
$customer_count = (int)$customer_count_result['CUSTOMER_COUNT'];

// Free the statement resource
oci_free_statement($stmt_count_customer_id);


// Define the SQL query to sum NO_OF_PRODUCT from ORDER_PRODUCT
$sql_sum_no_of_product = "
    SELECT SUM(OP.NO_OF_PRODUCT) AS TOTAL_NO_OF_PRODUCT
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_STATUS = 4
    AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_no_of_product .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_sum_no_of_product = oci_parse($conn, $sql_sum_no_of_product);

// Bind the parameters
oci_bind_by_name($stmt_sum_no_of_product, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_no_of_product, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_no_of_product);

// Fetch the result
$total_no_of_product_result = oci_fetch_assoc($stmt_sum_no_of_product);

// Get the total number of products
$total_no_of_product = (int)$total_no_of_product_result['TOTAL_NO_OF_PRODUCT'];

// Free the statement resource
oci_free_statement($stmt_sum_no_of_product);


// Define the SQL query to sum NO_OF_PRODUCT from ORDER_PRODUCT
$sql_sum_no_of_product_3 = "
    SELECT SUM(OP.NO_OF_PRODUCT) AS TOTAL_NO_OF_PRODUCT_3
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_STATUS = 3
    AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_no_of_product_3 .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_sum_no_of_product_3 = oci_parse($conn, $sql_sum_no_of_product_3);

// Bind the parameters
oci_bind_by_name($stmt_sum_no_of_product_3, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_no_of_product_3, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_no_of_product_3);

// Fetch the result
$total_no_of_product_result_3 = oci_fetch_assoc($stmt_sum_no_of_product_3);

// Get the total number of products
$total_no_of_product_3 = (int)$total_no_of_product_result_3['TOTAL_NO_OF_PRODUCT_3'];

// Free the statement resource
oci_free_statement($stmt_sum_no_of_product_3);




// Define the SQL query to select the product name with the highest sum of NO_OF_PRODUCT
$sql_highest_sum_product = "
    SELECT P.PRODUCT_NAME
    FROM PRODUCT P
    JOIN ORDER_DETAILS OD ON P.PRODUCT_ID = OD.PRODUCT_ID
    JOIN (
        SELECT OD.PRODUCT_ID, SUM(OP.NO_OF_PRODUCT) AS TOTAL_NO_OF_PRODUCT
        FROM ORDER_PRODUCT OP
        JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
        WHERE OP.ORDER_STATUS > 0
        AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_highest_sum_product .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

$sql_highest_sum_product .= "
        GROUP BY OD.PRODUCT_ID
        ORDER BY TOTAL_NO_OF_PRODUCT DESC
    ) T ON P.PRODUCT_ID = T.PRODUCT_ID
WHERE ROWNUM = 1
";

// Parse the SQL query
$stmt_highest_sum_product = oci_parse($conn, $sql_highest_sum_product);

// Bind the parameters
oci_bind_by_name($stmt_highest_sum_product, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_highest_sum_product, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_highest_sum_product);

// Fetch the result
$row_highest_sum_product = oci_fetch_assoc($stmt_highest_sum_product);

// Store the product name in a variable
$highest_sum_product_name = $row_highest_sum_product['PRODUCT_NAME'];

// Free the statement resource
oci_free_statement($stmt_highest_sum_product);


// Define the SQL query to select the product name with the lowest sum of NO_OF_PRODUCT
$sql_lowest_sum_product = "
    SELECT P.PRODUCT_NAME
    FROM PRODUCT P
    JOIN ORDER_DETAILS OD ON P.PRODUCT_ID = OD.PRODUCT_ID
    JOIN (
        SELECT OD.PRODUCT_ID, SUM(OP.NO_OF_PRODUCT) AS TOTAL_NO_OF_PRODUCT
        FROM ORDER_PRODUCT OP
        JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
        WHERE OP.ORDER_STATUS > 0
        AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_lowest_sum_product .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

$sql_lowest_sum_product .= "
        GROUP BY OD.PRODUCT_ID
        ORDER BY TOTAL_NO_OF_PRODUCT ASC -- Sort in ascending order
    ) T ON P.PRODUCT_ID = T.PRODUCT_ID
WHERE ROWNUM = 1
";

// Parse the SQL query
$stmt_lowest_sum_product = oci_parse($conn, $sql_lowest_sum_product);

// Bind the parameters
oci_bind_by_name($stmt_lowest_sum_product, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_lowest_sum_product, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_lowest_sum_product);

// Fetch the result
$row_lowest_sum_product = oci_fetch_assoc($stmt_lowest_sum_product);

// Store the product name in a variable
$lowest_sum_product_name = $row_lowest_sum_product['PRODUCT_NAME'];

// Free the statement resource
oci_free_statement($stmt_lowest_sum_product);



// Define the SQL query to select the highest TOTAL_PRICE from ORDER_PRODUCT
$sql_highest_total_price = "
    SELECT MAX(OP.TOTAL_PRICE) AS HIGHEST_TOTAL_PRICE
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_STATUS > 0
    AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_highest_total_price .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_highest_total_price = oci_parse($conn, $sql_highest_total_price);

// Bind the parameters
oci_bind_by_name($stmt_highest_total_price, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_highest_total_price, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_highest_total_price);

// Fetch the result
$row_highest_total_price = oci_fetch_assoc($stmt_highest_total_price);

// Store the highest total price in a variable
$highest_total_price = $row_highest_total_price['HIGHEST_TOTAL_PRICE'];

// Free the statement resource
oci_free_statement($stmt_highest_total_price);


// Define the SQL query to select the lowest TOTAL_PRICE from ORDER_PRODUCT
$sql_lowest_total_price = "
    SELECT MIN(OP.TOTAL_PRICE) AS LOWEST_TOTAL_PRICE
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_STATUS > 0
    AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_lowest_total_price .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_lowest_total_price = oci_parse($conn, $sql_lowest_total_price);

// Bind the parameters
oci_bind_by_name($stmt_lowest_total_price, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_lowest_total_price, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_lowest_total_price);

// Fetch the result
$row_lowest_total_price = oci_fetch_assoc($stmt_lowest_total_price);

// Store the lowest total price in a variable
$lowest_total_price = $row_lowest_total_price['LOWEST_TOTAL_PRICE'];

// Free the statement resource
oci_free_statement($stmt_lowest_total_price);


// Define the SQL query to sum DISCOUNT_AMOUNT from ORDER_PRODUCT
$sql_sum_discount_amount = "
    SELECT SUM(OP.DISCOUNT_AMOUNT) AS TOTAL_DISCOUNT_AMOUNT
    FROM ORDER_PRODUCT OP
    JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
    WHERE OP.ORDER_STATUS > 0
    AND OP.ORDER_TIME >= TO_DATE(:start_date, 'YYYY-MM-DD')
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_discount_amount .= " AND OD.TRADER_USER_ID = :trader_user_id";
}

// Parse the SQL query
$stmt_sum_discount_amount = oci_parse($conn, $sql_sum_discount_amount);

// Bind the parameters
oci_bind_by_name($stmt_sum_discount_amount, ':start_date', $start_date);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_discount_amount, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_discount_amount);

// Fetch the result
$row_sum_discount_amount = oci_fetch_assoc($stmt_sum_discount_amount);

// Get the total discount amount
$total_discount_amount = $row_sum_discount_amount['TOTAL_DISCOUNT_AMOUNT'];

// Free the statement resource
oci_free_statement($stmt_sum_discount_amount);


// Define the SQL query to sum PRODUCT_QUANTITY from PRODUCT
$sql_sum_product_quantity = "
    SELECT SUM(PRODUCT_QUANTITY) AS TOTAL_PRODUCT_QUANTITY
    FROM PRODUCT
    WHERE ADMIN_VERIFIED = 1
    AND IS_DISABLED = 1
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_sum_product_quantity .= " AND USER_ID = :trader_user_id";
}
// Parse the SQL query
$stmt_sum_product_quantity = oci_parse($conn, $sql_sum_product_quantity);

if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_sum_product_quantity, ':trader_user_id', $selected_trader);
}

// Execute the SQL query
oci_execute($stmt_sum_product_quantity);

// Fetch the result
$row_sum_product_quantity = oci_fetch_assoc($stmt_sum_product_quantity);

// Get the total product quantity
$total_product_quantity = $row_sum_product_quantity['TOTAL_PRODUCT_QUANTITY'];

// Free the statement resource
oci_free_statement($stmt_sum_product_quantity);


// Define the SQL query to count PRODUCT_QUANTITY from PRODUCT where PRODUCT_QUANTITY <= 0
$sql_count_product_quantity = "
    SELECT COUNT(*) AS QUANTITY_LE_ZERO
    FROM PRODUCT
    WHERE ADMIN_VERIFIED = 1
    AND IS_DISABLED = 1
    AND PRODUCT_QUANTITY <= 0
";

// Add the trader filter if necessary
if ($selected_trader !== 'all') {
    $sql_count_product_quantity .= " AND USER_ID = :trader_id";
}

// Parse the SQL query
$stmt_count_product_quantity = oci_parse($conn, $sql_count_product_quantity);

// Bind the parameters
if ($selected_trader !== 'all') {
    oci_bind_by_name($stmt_count_product_quantity, ':trader_id', $selected_trader);
}

// Execute the SQL query
if (oci_execute($stmt_count_product_quantity)) {
    // Fetch the result
    $row_count_product_quantity = oci_fetch_assoc($stmt_count_product_quantity);
    // Check if fetching was successful
    if ($row_count_product_quantity !== false) {
        // Get the count of product quantity less than or equal to zero
        $product_quantity_zero_count = $row_count_product_quantity['QUANTITY_LE_ZERO'];
    } else {
        // Handle fetch error
    }
} else {
    // Handle execute error
}

// Free the statement resource
oci_free_statement($stmt_count_product_quantity);




// Close the database connection
oci_close($conn);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_sales_report.css">
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
    <h1 class="page-title">Sales Report</h1>
    <div class="product-container">
    <div class="sort-container">
    <form id="traderForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="trader">Select Trader:</label>
                <select id="trader" name="trader" onchange="submitTraderForm()">
            <option value="all">All</option>
            <?php foreach ($user_shop_details as $detail): ?>
                <option value="<?php echo $detail['USER_ID']; ?>"><?php echo htmlspecialchars($detail['SHOP_NAME']); ?></option>
            <?php endforeach; ?>
        </select>
            </form>
        </div>
        <div class="search-container">
        <form id="time_periodForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="time_period">Time Period:</label>
                <select id="time_period" name="time_period" onchange="submitTimePeriodForm()">
                    <option value="1">1 Day</option>
                    <option value="7">7 Days</option>
                    <option value="15">15 Days</option>
                    <option value="30">30 Days</option>
                </select>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="graph-section">
        <h2>Sales vs Time</h2>
            <canvas id="orderGraph"></canvas>
        </div>
        <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="text">Total Product Sales: <span class="number"><?php echo $total_qty; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-pound-sign"></i></div>
            <div class="text">Total Sales: <span class="number">£ <?php echo $total_price_sum; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="text">Total Customers Served: <span class="number"><?php echo $customer_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <div class="text">Total Items Delivered: <span class="number"><?php echo $total_no_of_product; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i><i class="fas fa-box"></i></div>
            <div class="text">Items to be Delivered: <span class="number"><?php echo $total_no_of_product_3; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-trophy"></i></div>
            <div class="text">Most Sold Product: <span class="product-name"><?php echo $highest_sum_product_name; ?></span></div>
        </div>
        </div>
    </div>
    <div class="container">
    <div class="bar-section">
        
        <canvas id="salesbar"></canvas>
    </div>
    <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-wine-bottle"></i></div>
            <div class="text">Least Selling Product: <span class="product-name"><?php echo $lowest_sum_product_name; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="text">Highest Sales Amount: <span class="number">£ <?php echo $highest_total_price; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
            <div class="text">Lowest Sales Amount: <span class="number">£ <?php echo $lowest_total_price; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-tags"></i></div>
            <div class="text">Total Discount Amount: <span class="number">£ <?php echo $total_discount_amount; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-cubes"></i></div>
            <div class="text">Total Saleable Products: <span class="number"><?php echo $total_product_quantity; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <div class="text">Total Out of Stock Products: <span class="number"><?php echo $product_quantity_zero_count; ?></span></div>
        </div>
    </div>
</div>
<div class="salesBarGraph-container">
<h2>Sales Analysis</h2>
        <canvas id="salesBarGraph"></canvas>
</div>

    <script src="admin_product.js"></script>
     <!-- Include Chart.js library -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Plugin Annotations -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script src="admin_sales_report.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get selected values from local storage
            var selectedTraderValue = localStorage.getItem("selectedTraderValue");
            var selectedTimePeriodValue = localStorage.getItem("selectedTimePeriodValue");

            // Set selected values in the select elements
            if (selectedTraderValue) {
                document.getElementById("trader").value = selectedTraderValue;
            }

            if (selectedTimePeriodValue) {
                document.getElementById("time_period").value = selectedTimePeriodValue;
            }
        });

        function submitTraderForm() {
            var sortSelect = document.getElementById("trader");
            localStorage.setItem("selectedTraderValue", sortSelect.value);
            document.getElementById("traderForm").submit();
        }

        function submitTimePeriodForm() {
            var sortSelect = document.getElementById("time_period");
            localStorage.setItem("selectedTimePeriodValue", sortSelect.value);
            document.getElementById("time_periodForm").submit();
        }

        var ctxPie = document.getElementById('salesbar').getContext('2d');
        var myPieChart;

// Function to create and update the pie chart
function createOrUpdatePieChart(labels, data) {
    if (myPieChart) {
        myPieChart.destroy(); // Clear previous chart
    }
    myPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Portions of Products Sold',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}

// Assuming $order_times is an array
<?php
// Assuming $top_products is the array containing top 5 products

// Initialize arrays to store pie chart labels and data
$pieLabels = array();
$pieData = array();

// Iterate over the top_products array to extract product names and quantities
foreach ($top_products as $product) {
    // Extract product name and total quantity sold
    $productName = $product['PRODUCT_NAME'];
    $totalQty = (int)$product['TOTAL_QTY']; // Convert to integer for consistency

    // Append product name to pieLabels array
    $pieLabels[] = $productName;

    // Append total quantity sold to pieData array
    $pieData[] = $totalQty;
}

// Now, $pieLabels contains product names and $pieData contains total quantities sold
// You can use these arrays to create or update the pie chart
?>

createOrUpdatePieChart(<?php echo json_encode($pieLabels); ?>, <?php echo json_encode($pieData); ?>);

 // Function to create the sales graph
 function createSalesGraph(products, timesSold) {
            // Get canvas element
            var ctx = document.getElementById('salesBarGraph').getContext('2d');

            // Create the bar graph
            var salesBarGraph = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: products,
                    datasets: [{
                        label: 'Number of Times Sold',
                        data: timesSold,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Blue color with opacity
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }

        // PHP data from server-side
        var products = <?php echo json_encode($productNames); ?>;
        var timesSold = <?php echo json_encode($productQuantities); ?>;

        // Call the function to create the sales graph
        createSalesGraph(products, timesSold);


        window.onload = function () {
    var ctx = document.getElementById('orderGraph').getContext('2d');
    var myChart;

    // Fetch PHP data as a JavaScript array
    var orderTimes = <?php echo json_encode($order_times); ?>;

    // Function to create and update the chart
    function createOrUpdateChart(labels, data) {
        if (myChart) {
            myChart.destroy(); // Clear previous chart
        }
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Order Number vs Time',
                    data: data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    datalabels: {
                        display: false
                    }
                }
            }
        });
    }

    function updateChartData() {
    var labels = [];
    var data = [];

    // Sort order times chronologically
    orderTimes.sort(function (a, b) {
        return new Date(a) - new Date(b);
    });

    // Check if all dates are the same
    var sameDate = orderTimes.every(function (time, index, arr) {
        return new Date(time).toDateString() === new Date(arr[0]).toDateString();
    });

    if (sameDate) {
        // Create labels for hours from 0:00 to 23:00
        for (var i = 0; i < 24; i++) {
            var hour = i < 10 ? '0' + i : i;
            labels.push(hour + ':00');
        }

        // Count the occurrences of each hour
        for (var j = 0; j < 24; j++) {
            var count = orderTimes.filter(function (time) {
                return new Date(time).getHours() === j;
            }).length;
            data.push(count);
        }
    } else {
        // Create labels for each date
        var startDate = new Date(orderTimes[0]);
        var endDate = new Date(orderTimes[orderTimes.length - 1]);
        var currentDate = new Date(startDate);

        while (currentDate <= endDate) {
            labels.push(currentDate.toISOString().slice(0, 10));
            var count = orderTimes.filter(function (time) {
                return new Date(time).toISOString().slice(0, 10) === currentDate.toISOString().slice(0, 10);
            }).length;
            data.push(count);
            currentDate.setDate(currentDate.getDate() + 1); // Increment by 1 day
        }
    }

    createOrUpdateChart(labels, data);
}


    // Call updateChartData function to initialize the chart
    updateChartData();
};

    </script>
</body>
</html>