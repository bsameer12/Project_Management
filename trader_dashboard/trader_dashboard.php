<?php
// Initialize connection and other necessary variables
require("trader_session.php");
include("../connection/connection.php");

// Initialize variables for placeholders
$trader_user_id = $_SESSION["userid"];

// Construct the SQL statement to select ORDER_TIME from ORDER_PRODUCT table
$sql_order_time = "SELECT DISTINCT TO_CHAR(OP.ORDER_TIME, 'YYYY-MM-DD\"T\"HH24:MI:SS') AS ORDER_TIME
FROM ORDER_PRODUCT OP
JOIN ORDER_DETAILS OD ON OP.ORDER_PRODUCT_ID = OD.ORDER_PRODUCT_ID
WHERE OD.TRADER_USER_ID = :trader_user_id";


// Prepare the statement
$stmt_order_time = oci_parse($conn, $sql_order_time);

// Bind the parameters
oci_bind_by_name($stmt_order_time, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_order_time);

// Fetch the data into an array
$order_times = array();
while ($row = oci_fetch_assoc($stmt_order_time)) {
    $order_times[] = $row['ORDER_TIME'];
}

// Free the statement
oci_free_statement($stmt_order_time);

// Construct the SQL statement to count orders with ORDER_STATUS = 0
$sql_order_count = "
SELECT COUNT(DISTINCT OP.ORDER_PRODUCT_ID) AS PENDING_ORDERS_COUNT
FROM ORDER_DETAILS OD
JOIN ORDER_PRODUCT OP ON OD.ORDER_PRODUCT_ID = OP.ORDER_PRODUCT_ID
WHERE OD.TRADER_USER_ID = :trader_user_id AND OP.ORDER_STATUS = 0";

// Prepare the statement
$stmt_order_count = oci_parse($conn, $sql_order_count);

// Bind the parameters
oci_bind_by_name($stmt_order_count, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_order_count);

// Fetch the count
$row = oci_fetch_assoc($stmt_order_count);
$pending_orders_count = $row['PENDING_ORDERS_COUNT'];

// Free the statement
oci_free_statement($stmt_order_count);

// Get today's date in the format used by the database
$current_date = date('Y-m-d');

// Construct the SQL statement to count today's orders
$sql_today_order_count = "
SELECT COUNT(DISTINCT OP.ORDER_PRODUCT_ID) AS TODAY_ORDERS_COUNT
FROM ORDER_DETAILS OD
JOIN ORDER_PRODUCT OP ON OD.ORDER_PRODUCT_ID = OP.ORDER_PRODUCT_ID
WHERE OD.TRADER_USER_ID = :trader_user_id 
AND TO_CHAR(OP.ORDER_DATE, 'YYYY-MM-DD') = :current_date";

// Prepare the statement
$stmt_today_order_count = oci_parse($conn, $sql_today_order_count);

// Bind the parameters
oci_bind_by_name($stmt_today_order_count, ':trader_user_id', $trader_user_id);
oci_bind_by_name($stmt_today_order_count, ':current_date', $current_date);

// Execute the statement
oci_execute($stmt_today_order_count);

// Fetch the count
$row = oci_fetch_assoc($stmt_today_order_count);
$today_orders_count = $row['TODAY_ORDERS_COUNT'];

// Free the statement
oci_free_statement($stmt_today_order_count);

// Construct the SQL statement to count the products
$sql_product_count = "
SELECT COUNT(PRODUCT_ID) AS PRODUCT_COUNT
FROM PRODUCT
WHERE USER_ID = :trader_user_id";

// Prepare the statement
$stmt_product_count = oci_parse($conn, $sql_product_count);

// Bind the parameters
oci_bind_by_name($stmt_product_count, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_product_count);

// Fetch the count
$row = oci_fetch_assoc($stmt_product_count);
$product_count = $row['PRODUCT_COUNT'];

// Free the statement
oci_free_statement($stmt_product_count);

// Construct the SQL statement to select distinct PRODUCT_ID from ORDER_DETAILS
$sql_product_count = "SELECT COUNT(cs.SLOT_DATE) AS product_count
                      FROM ORDER_DETAILS od
                      JOIN COLLECTION_SLOT cs ON od.ORDER_PRODUCT_ID = cs.ORDER_PRODUCT_ID
                      WHERE od.TRADER_USER_ID = :trader_user_id
                      AND TRUNC(cs.SLOT_DATE) = TRUNC(SYSDATE)";

// Prepare the statement
$stmt_product_count = oci_parse($conn, $sql_product_count);

// Bind the parameters
oci_bind_by_name($stmt_product_count, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_product_count);

// Fetch the data into a variable
$order_today_count = 0; // Initialize with 0
if ($row = oci_fetch_assoc($stmt_product_count)) {
    $order_today_count = $row['PRODUCT_COUNT'];
}

// Free the statement
oci_free_statement($stmt_product_count);

// Construct the SQL statement to calculate the average REVIEW_SCORE
$sql_avg_review_score = "SELECT AVG(REVIEW_SCORE) AS avg_review_score
                         FROM REVIEW r
                         JOIN ORDER_DETAILS od ON r.ORDER_ID = od.ORDER_PRODUCT_ID
                         WHERE od.TRADER_USER_ID = :trader_user_id
                         AND r.REVIEW_PROCIDED = 1";

// Prepare the statement
$stmt_avg_review_score = oci_parse($conn, $sql_avg_review_score);

// Bind the parameters
oci_bind_by_name($stmt_avg_review_score, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_avg_review_score);

// Fetch the data into a variable
$avg_review_score = 0; // Initialize with 0
if ($row = oci_fetch_assoc($stmt_avg_review_score)) {
    $avg_review_score = $row['AVG_REVIEW_SCORE'];
}

// Free the statement
oci_free_statement($stmt_avg_review_score);

// Get today's date
$today_date = date('Y-m-d');

// Construct the SQL statement to count SLOT_DATE
$sql_count_slot_date = "SELECT COUNT(cs.SLOT_DATE) AS slot_date_count
                        FROM COLLECTION_SLOT cs
                        WHERE cs.SLOT_DATE = (
                            SELECT MIN(SLOT_DATE) 
                            FROM COLLECTION_SLOT 
                            WHERE SLOT_DATE > TO_DATE(:today_date, 'YYYY-MM-DD')
                        )
                        AND cs.ORDER_PRODUCT_ID IN (
                            SELECT DISTINCT(od.ORDER_PRODUCT_ID) 
                            FROM ORDER_DETAILS od 
                            WHERE od.TRADER_USER_ID = :trader_user_id
                        )";

// Prepare the statement
$stmt_count_slot_date = oci_parse($conn, $sql_count_slot_date);

// Bind the parameters
oci_bind_by_name($stmt_count_slot_date, ':today_date', $today_date);
oci_bind_by_name($stmt_count_slot_date, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_count_slot_date);

// Fetch the data into a variable
$slot_date_count = 0; // Initialize with 0
if ($row = oci_fetch_assoc($stmt_count_slot_date)) {
    $slot_date_count = $row['SLOT_DATE_COUNT'];
}

// Free the statement
oci_free_statement($stmt_count_slot_date);

// Construct the SQL statement
$sql_top_products = "SELECT * FROM (
    SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PICTURE, AVG(r.REVIEW_SCORE) AS avg_score
    FROM PRODUCT p
    JOIN REVIEW r ON p.PRODUCT_ID = r.PRODUCT_ID
    WHERE p.USER_ID = :trader_user_id
    GROUP BY p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PICTURE
    ORDER BY avg_score DESC
) WHERE ROWNUM <= 5";

// Prepare the statement
$stmt_top_products = oci_parse($conn, $sql_top_products);

// Bind the parameters
oci_bind_by_name($stmt_top_products, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_top_products);

// Fetch the data into an array
$top_products = array();
while ($row = oci_fetch_assoc($stmt_top_products)) {
    $product_info = array(
        'PRODUCT_ID' => $row['PRODUCT_ID'],
        'PRODUCT_NAME' => $row['PRODUCT_NAME'],
        'PRODUCT_PICTURE' => $row['PRODUCT_PICTURE'],
        'AVERAGE_SCORE' => $row['AVG_SCORE']
    );
    $top_products[] = $product_info;
}

// Free the statement
oci_free_statement($stmt_top_products);


// Construct the SQL statement
$sql_products = "SELECT PRODUCT_NAME, PRODUCT_PICTURE 
                 FROM PRODUCT 
                 WHERE USER_ID = :trader_user_id 
                 AND PRODUCT_QUANTITY < 110";

// Prepare the statement
$stmt_products = oci_parse($conn, $sql_products);

// Bind the parameters
oci_bind_by_name($stmt_products, ':trader_user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_products);

// Fetch the data into an array
$products = array();
while ($row = oci_fetch_assoc($stmt_products)) {
    $product_info = array(
        'PRODUCT_NAME' => $row['PRODUCT_NAME'],
        'PRODUCT_PICTURE' => $row['PRODUCT_PICTURE']
    );
    $products[] = $product_info;
}

// Free the statement
oci_free_statement($stmt_products);

$sql_data = "SELECT 
    r.REVIEW_SCORE, 
    r.FEEDBACK, 
    u.FIRST_NAME || ' ' || u.LAST_NAME AS NAME, 
    u.USER_PROFILE_PICTURE 
FROM 
    REVIEW r 
JOIN 
    HUDDER_USER u ON r.USER_ID = u.USER_ID 
WHERE 
    r.REVIEW_PROCIDED = 1 
    AND r.PRODUCT_ID IN (SELECT PRODUCT_ID FROM PRODUCT WHERE USER_ID = :user_id)";

// Prepare the statement
$stmt_data = oci_parse($conn, $sql_data);

// Bind the parameters
oci_bind_by_name($stmt_data, ':user_id', $trader_user_id);

// Execute the statement
oci_execute($stmt_data);

// Initialize an array to store the results
$user_review = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt_data)) {
    $user_review[] = $row;
}

// Free the statement
oci_free_statement($stmt_data);

// Close the connection
oci_close($conn);





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Dashboard</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_dashboard.css">
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
    <div class="container">
        <div class="graph-section">
            <canvas id="orderGraph"></canvas>
        </div>
        <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="text">Incomplete Orders: <span class="number"> <?php echo $pending_orders_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="text">Orders Received Today: <span class="number"><?php echo $today_orders_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-box"></i></div>
            <div class="text">Total Products: <span class="number"><?php echo $product_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <div class="text">Orders to be Delivered Today: <span class="number"><?php echo $order_today_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-star"></i></div>
            <div class="text">Rating: <span class="number"><?php echo $avg_review_score; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="text">Orders to be Delivered Next Working Day: <span class="number"><?php echo $slot_date_count; ?></span></div>
        </div>
        </div>
    </div>
    <div class="uniqueContainer" id="uniqueContainer">
        <section class="left-section">
            <h2>Top 5 Products</h2>
            <table>
            <?php
        foreach ($top_products as $product) {
            // Generate stars based on the average score
            $average_score = $product['AVERAGE_SCORE'];
            $stars_html = '';
            for ($i = 0; $i < round($average_score); $i++) {
                $stars_html .= '<span class="star"></span>';
            }

            // Output product information in each row
            echo '<tr>';
            echo '<td><img src="../product_image/' . $product['PRODUCT_PICTURE'] . '" alt="Product Image"></td>';
            echo '<td>' . $product['PRODUCT_NAME'] . '</td>';
            echo '<td><div class="stars">' . $stars_html . '</div></td>';
            echo '</tr>';
        }
        ?>
            </table>
        </section>
        <section class="right-section">
            <h2>Low Quantity Products</h2>
            <table>
            <?php
            // Loop through the products array and generate HTML for each product
            foreach ($products as $product) {
                echo '<tr>';
                echo '<td><img src="../product_image/' . $product['PRODUCT_PICTURE'] . '" alt="Product Image"></td>';
                echo '<td>' . $product['PRODUCT_NAME'] . '</td>';
                echo '<td>Low</td>'; // This is based on your provided HTML structure
                echo '</tr>';
            }
            ?>
            </table>
        </section>
    </div>
    <div class="comment" id="comment">
        <h2>Recent Comments</h2>
        <table class="comments-table">
            <?php
        // Loop through the fetched data and generate HTML for each row
foreach ($user_review as $row) {
    // Extract data from the current row
    $review_score = $row['REVIEW_SCORE'];
    $name = $row['NAME'];
    $user_profile_picture = $row['USER_PROFILE_PICTURE'];

    // Generate HTML for the user profile image
    echo '<tr>';
    echo '<td class="user-profile">';
    echo '<img src="../profile_image/' . $user_profile_picture . '" alt="Profile Image" class="profile-image">';
    echo '</td>';

    // Generate HTML for the comment details
    echo '<td class="comment-details">';
    echo '<div class="user-name">' . $name . '</div>';
    echo '<div class="user-rating">';
    // Generate HTML for the star rating (assuming $review_score represents the rating)
    for ($i = 0; $i < $review_score; $i++) {
        echo '<span class="star"></span>';
    }
    echo '</div>';
    echo '<div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>'; // You can customize the review text
    echo '</td>';
    echo '</tr>';
}
?>



            <!-- Add more comments with dummy data -->
        </table>
    </div>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Plugin Annotations -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    
    <script src="trader_navbar.js"> </script>
    <script>
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

        // Function to update chart data based on selected date range and fetched order times
        function updateChartData(selectedValue) {
            var labels = [];
            var data = [];

            var now = new Date();
            var oneDayAgo = new Date(now);
            oneDayAgo.setDate(oneDayAgo.getDate() - 1);
            var sevenDaysAgo = new Date(now);
            sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
            var thirtyDaysAgo = new Date(now);
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

            function fillMissingDays(startDate, endDate) {
                var date = new Date(startDate);
                var dates = [];
                while (date <= endDate) {
                    dates.push(new Date(date));
                    date.setDate(date.getDate() + 1);
                }
                return dates;
            }

            if (selectedValue === 1) {
                // For 1 Day
                var hoursCount = Array(24).fill(0);
                orderTimes.forEach(function (time) {
                    var date = new Date(time);
                    if (date >= oneDayAgo) {
                        var hour = date.getHours();
                        hoursCount[hour]++;
                    }
                });
                labels = hoursCount.map((_, i) => i + ':00');
                data = hoursCount;
            } else if (selectedValue === 7) {
                // For 7 Days
                var daysCount = {};
                orderTimes.forEach(function (time) {
                    var date = new Date(time);
                    if (date >= sevenDaysAgo) {
                        var dateString = date.toISOString().split('T')[0];
                        if (!daysCount[dateString]) {
                            daysCount[dateString] = 0;
                        }
                        daysCount[dateString]++;
                    }
                });

                var allDates = fillMissingDays(sevenDaysAgo, now);
                labels = allDates.map(date => date.toISOString().split('T')[0]);
                data = labels.map(date => daysCount[date] || 0);
            } else if (selectedValue === 30) {
                // For 30 Days
                var daysCount = {};
                orderTimes.forEach(function (time) {
                    var date = new Date(time);
                    if (date >= thirtyDaysAgo) {
                        var dateString = date.toISOString().split('T')[0];
                        if (!daysCount[dateString]) {
                            daysCount[dateString] = 0;
                        }
                        daysCount[dateString]++;
                    }
                });

                var allDates = fillMissingDays(thirtyDaysAgo, now);
                labels = allDates.map(date => date.toISOString().split('T')[0]);
                data = labels.map(date => daysCount[date] || 0);
            }

            createOrUpdateChart(labels, data);
        }

        // Call updateChartData function with default value 1 to display data for 1 Day
        updateChartData(1);

        // Create a select dropdown for date range selection
        var dateRangeSelect = document.createElement('select');
        dateRangeSelect.innerHTML = `
            <option value="1" selected>1 Day</option>
            <option value="7">7 Days</option>
            <option value="30">30 Days</option>
        `;
        dateRangeSelect.addEventListener('change', function () {
            var selectedValue = parseInt(dateRangeSelect.value);
            updateChartData(selectedValue);
        });

        // Append the select dropdown to the .graph-section element
        var graphSection = document.querySelector('.graph-section');
        graphSection.appendChild(dateRangeSelect);
    };
</script>

</body>
</html>
