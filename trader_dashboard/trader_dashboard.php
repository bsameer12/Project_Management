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
            <div class="text">Pending Orders: <span class="number">10</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="text">Orders Received Today: <span class="number">20</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-box"></i></div>
            <div class="text">Total Products: <span class="number">100</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <div class="text">Orders to be Delivered Today: <span class="number">5</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-star"></i></div>
            <div class="text">Rating: <span class="number">4.5</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="text">Orders to be Delivered Next Working Day: <span class="number">8</span></div>
        </div>
        </div>
    </div>
    <div class="uniqueContainer" id="uniqueContainer">
        <section class="left-section">
            <h2>Top 5 Products</h2>
            <table>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 1</td>
                    <td>
                        <div class="stars">
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 2</td>
                    <td>
                        <div class="stars">
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 3</td>
                    <td>
                        <div class="stars">
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 4</td>
                    <td>
                        <div class="stars">
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 5</td>
                    <td>
                        <div class="stars">
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                            <span class="star"></span>
                        </div>
                    </td>
                </tr>
                <!-- Add more rows with dummy data -->
            </table>
        </section>
        <section class="right-section">
            <h2>Low Quantity Products</h2>
            <table>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 2</td>
                    <td>Low</td>
                </tr>
                <!-- Add more rows with dummy data -->
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 3</td>
                    <td>Low</td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 12</td>
                    <td>Low</td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 28</td>
                    <td>Low</td>
                </tr>
                <tr>
                    <td><img src="../caviber_image.jpg" alt="Product Image"></td>
                    <td>Product 112</td>
                    <td>Low</td>
                </tr>
            </table>
        </section>
    </div>
    <div class="comment" id="comment">
        <h2>Recent Comments</h2>
        <table class="comments-table">
            <tr>
                <td class="user-profile">
                    <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                </td>
                <td class="comment-details">
                    <div class="user-name">User 1</div>
                    <div class="user-rating">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                    <div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                </td>
            </tr>
            <!-- Add more comments with dummy data -->
            <tr>
                <td class="user-profile">
                    <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                </td>
                <td class="comment-details">
                    <div class="user-name">User 2</div>
                    <div class="user-rating">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                    <div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                </td>
            </tr>
            <tr>
                <td class="user-profile">
                    <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                </td>
                <td class="comment-details">
                    <div class="user-name">User 3</div>
                    <div class="user-rating">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                    <div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                </td>
            </tr>
            <tr>
                <td class="user-profile">
                    <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                </td>
                <td class="comment-details">
                    <div class="user-name">User 4</div>
                    <div class="user-rating">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                    <div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                </td>
            </tr>
            <tr>
                <td class="user-profile">
                    <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                </td>
                <td class="comment-details">
                    <div class="user-name">User 5</div>
                    <div class="user-rating">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                    <div class="user-review">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                </td>
            </tr>
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
