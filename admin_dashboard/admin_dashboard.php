<?php
 include("admin_session.php");
// Initialize connection and other necessary variables
include("../connection/connection.php");


// Construct the SQL statement to select ORDER_TIME from ORDER_PRODUCT table
$sql_order_time = "SELECT TO_CHAR(ORDER_TIME, 'YYYY-MM-DD\"T\"HH24:MI:SS') AS ORDER_TIME
                   FROM ORDER_PRODUCT";

// Prepare the statement
$stmt_order_time = oci_parse($conn, $sql_order_time);

// Execute the statement
oci_execute($stmt_order_time);

// Fetch the data into an array
$order_times = array();
while ($row = oci_fetch_assoc($stmt_order_time)) {
    $order_times[] = $row['ORDER_TIME'];
}

// Free the statement
oci_free_statement($stmt_order_time);


// Construct the SQL statement to count USER_IDs
$sql_count_customers = "SELECT COUNT(USER_ID) AS CUSTOMER_COUNT FROM HUDDER_USER WHERE USER_TYPE = 'customer'";

// Prepare the statement
$stmt_count_customers = oci_parse($conn, $sql_count_customers);

// Execute the statement
oci_execute($stmt_count_customers);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_customers);
$customer_count = $row['CUSTOMER_COUNT'];

// Free the statement
oci_free_statement($stmt_count_customers);


// Construct the SQL statement to count USER_IDs for traders
$sql_count_traders = "SELECT COUNT(USER_ID) AS TRADER_COUNT FROM HUDDER_USER WHERE USER_TYPE = 'trader'";

// Prepare the statement
$stmt_count_traders = oci_parse($conn, $sql_count_traders);

// Execute the statement
oci_execute($stmt_count_traders);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_traders);
$trader_count = $row['TRADER_COUNT'];

// Free the statement
oci_free_statement($stmt_count_traders);


// Construct the SQL statement to count PRODUCT_IDs
$sql_count_products = "SELECT COUNT(PRODUCT_ID) AS PRODUCT_COUNT FROM PRODUCT";

// Prepare the statement
$stmt_count_products = oci_parse($conn, $sql_count_products);

// Execute the statement
oci_execute($stmt_count_products);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_products);
$product_count = $row['PRODUCT_COUNT'];

// Free the statement
oci_free_statement($stmt_count_products);


// Construct the SQL statement to count rows where VERFIED_ADMIN = 0
$sql_count_unverified_traders = "SELECT COUNT(*) AS UNVERIFIED_TRADERS_COUNT FROM TRADER WHERE VERFIED_ADMIN = 0";

// Prepare the statement
$stmt_count_unverified_traders = oci_parse($conn, $sql_count_unverified_traders);

// Execute the statement
oci_execute($stmt_count_unverified_traders);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_unverified_traders);
$unverified_traders_count = $row['UNVERIFIED_TRADERS_COUNT'];

// Free the statement
oci_free_statement($stmt_count_unverified_traders);


// Construct the SQL statement to count rows where ADMIN_VERIFIED = 0
$sql_count_unverified_products = "SELECT COUNT(*) AS UNVERIFIED_PRODUCTS_COUNT FROM PRODUCT WHERE ADMIN_VERIFIED = 0";

// Prepare the statement
$stmt_count_unverified_products = oci_parse($conn, $sql_count_unverified_products);

// Execute the statement
oci_execute($stmt_count_unverified_products);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_unverified_products);
$unverified_products_count = $row['UNVERIFIED_PRODUCTS_COUNT'];

// Free the statement
oci_free_statement($stmt_count_unverified_products);

// Get today's date in the format used by the database
$current_date = date('Y-m-d');

// Construct the SQL statement to count rows where ORDER_DATE = current date
$sql_count_today_orders = "SELECT COUNT(*) AS TODAY_ORDERS_COUNT FROM ORDER_PRODUCT WHERE TRUNC(ORDER_DATE) = TO_DATE(:current_date, 'YYYY-MM-DD')";

// Prepare the statement
$stmt_count_today_orders = oci_parse($conn, $sql_count_today_orders);

// Bind the parameters
oci_bind_by_name($stmt_count_today_orders, ':current_date', $current_date);

// Execute the statement
oci_execute($stmt_count_today_orders);

// Fetch the result
$row = oci_fetch_assoc($stmt_count_today_orders);
$today_orders_count = $row['TODAY_ORDERS_COUNT'];

// Free the statement
oci_free_statement($stmt_count_today_orders);


// Construct the SQL statement
$sql = "SELECT s.SHOP_NAME, s.SHOP_PROFILE
FROM (
    SELECT SHOP_NAME, SHOP_PROFILE, COUNT(od.ORDER_PRODUCT_ID) AS ORDER_COUNT
    FROM SHOP s
    JOIN ORDER_DETAILS od ON s.USER_ID = od.TRADER_USER_ID
    GROUP BY SHOP_NAME, SHOP_PROFILE
    ORDER BY COUNT(od.ORDER_PRODUCT_ID) DESC
) s
WHERE ROWNUM <= 5
";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

// Execute the statement
oci_execute($stmt);

// Initialize an array to store the results
$top_shops = array();

// Fetch the results into the array
while ($row = oci_fetch_assoc($stmt)) {
    $top_shops[] = $row;
}

// Free the statement
oci_free_statement($stmt);


// Construct the SQL statement
$sql_top_products = "SELECT * FROM (
    SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PICTURE, AVG(r.REVIEW_SCORE) AS avg_score
    FROM PRODUCT p
    JOIN REVIEW r ON p.PRODUCT_ID = r.PRODUCT_ID
    GROUP BY p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PICTURE
    ORDER BY avg_score DESC
) WHERE ROWNUM <= 5";

// Prepare the statement
$stmt_top_products = oci_parse($conn, $sql_top_products);

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
    AND r.PRODUCT_ID IN (SELECT PRODUCT_ID FROM PRODUCT)";

// Prepare the statement
$stmt_data = oci_parse($conn, $sql_data);

// Execute the statement
oci_execute($stmt_data);

// Initialize an array to store the results
$user_reviews = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt_data)) {
    $user_reviews[] = $row;
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
    <title>Admin Dashboard</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_dashboard.css">
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
    <div class="container">
        <div class="graph-section">
            <canvas id="orderGraph"></canvas>
        </div>
        <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="text">Total Customers: <span class="number"><?php echo $customer_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-user-tie"></i></div>
            <div class="text">Total Traders: <span class="number"><?php echo $trader_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-box-open"></i></div>
            <div class="text">Total Products: <span class="number"><?php echo $product_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <div class="text">Pending Trader Verification: <span class="number"><?php echo $unverified_traders_count; ?></span></div>
        </div>
        <div class="card">
        <div class="icon"><i class="fas fa-box"></i><i class="fas fa-check-circle"></i></div>
            <div class="text">Pending Product Verification: <span class="number"><?php echo $unverified_products_count; ?></span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-calendar-day"></i></div>
            <div class="text">Total Orders Today: <span class="number"><?php echo $today_orders_count; ?></span></div>
        </div>
        </div>
    </div>
    <div class="uniqueContainer" id="uniqueContainer">
        <section class="left-section">
            <h2>Top 5 Traders</h2>
            <table>
            <?php
            foreach ($top_shops as $shop) {
                echo '<tr>';
                echo '<td><img src="../shop_profile_image/' . $shop['SHOP_PROFILE'] . '" alt="Shop Image"></td>';
                echo '<td>' . $shop['SHOP_NAME'] . '</td>';
                echo '</tr>';
            }
            ?>
            </table>
        </section>
        <section class="right-section">
            <h2>Top 5 products</h2>
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
    </div>
    <div class="comment" id="comment">
        <h2>Recent Comments</h2>
        <table class="comments-table">
        <?php
        // Loop through the fetched data and generate HTML for each row
foreach ($user_reviews as $row) {
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

         </table>
    </div>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Plugin Annotations -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    
    
    <script src="admin_navbar.js"> </script>
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
