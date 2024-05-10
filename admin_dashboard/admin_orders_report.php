<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Report</title>
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
    <h1 class="page-title">Orders Report</h1>
    <div class="product-container">
    <div class="sort-container">
            <form id="traderForm">
                <label for="trader">Select Trader:</label>
                <select id="trader" onchange="submitTraderForm()">
                    <option value="all">All</option>
                    <option value="Trader 1">Trader 1</option>
                    <option value="Trader 2">Trader 2</option>
                    <option value="Trader 3">Trader 3</option>
                    <option value="Trader 4">Trader 4</option>
                    <option value="Trader 5">Trader 5</option>
                </select>
            </form>
        </div>
        <div class="search-container">
        <form id="time_periodForm">
                <label for="time_period">Time Period:</label>
                <select id="time_period" onchange="submitTimePeriodForm()">
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
            <canvas id="orderGraph"></canvas>
        </div>
        <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="text">Total Product Orders: <span class="number">500</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-pound-sign"></i></div>
            <div class="text">Total Order Amount: <span class="number">£500</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="text">Total Customers: <span class="number">200</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <div class="text">Total Items Order Completed: <span class="number">1000</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i><i class="fas fa-box"></i></div>
            <div class="text">Uncompleted Orders: <span class="number">10</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="text">Well-Stocked Product: <span class="number">2</span></div>
        </div>
        </div>
    </div>
    <div class="container">
    <div class="bar-section">
        <canvas id="salesbar"></canvas>
    </div>
    <div class="cards-section">
        <div class="card">
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="text">Low Stock Product: <span class="number">5</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-ban"></i></div>
            <div class="text">Total Disabled Products:  <span class="number">100</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <div class="text">Items to be delivered next working day: <span class="number">100</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
            <div class="text">Total Orders to be Delivered Next Working Day: <span class="number">10</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-cubes"></i></div>
            <div class="text">Total  Products In Inventory: <span class="number">1000</span></div>
        </div>
        <div class="card">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <div class="text">Total Out of Stock Products: <span class="number">50</span></div>
        </div>
    </div>
</div>
<div class="salesBarGraph-container">
<h2>Order Analysis</h2>
        <canvas id="salesBarGraph"></canvas>
</div>

    <script src="admin_product.js"></script>
     <!-- Include Chart.js library -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Plugin Annotations -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script src="admin_order_report.js"></script>
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
    </script>
</body>
</html>