    <nav class="navbar">
        <a href="admin_dashboard.php" class="logo"><img src="../logo.png" alt="logo" class="logo-image"></a>
        <h1 class="heading">Admin Dashboard</h1>
        <div class="user-info">
                <span class="welcome">Welcome,</span>
                <span class="trader-name">  Admin Name</span>
                <img src="../profile.jpg" alt="Profile Image" class="profile-image">
                <div class="dropdown">
                    <ul>
                        <li><a href="admin_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="admin_change_password.php"><i class="fas fa-lock"></i> Change Password</a></li>
                        <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
        </div>
    </nav>

    <div class="sidebar">
        <div class="navbar-buttons">
            <button class="navbar-button" onclick="window.location.href='admin_dashboard.php' ; return false;"><i class="fas fa-tachometer-alt"></i> <span class="button-text">Dashboard</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_customer.php' ; return false;"><i class="fas fa-shopping-cart"></i> <span class="button-text">Customers</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_trader.php' ; return false;"><i class="fas fa-users"></i> <span class="button-text">Traders</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_shop_detail.php' ; return false;"><i class="fas fa-store"></i> <span class="button-text">Shops</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_review_rating.php' ; return false;"><i class="fas fa-star"></i> <span class="button-text">Ratings and Reviews</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_product.php' ; return false;"><i class="fas fa-box-open"></i> <span class="button-text">Products</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_trader_verification.php' ; return false;"><i class="fas fa-check-circle"></i> <span class="button-text">Trader Verification</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_product_verification.php' ; return false;"><i class="fas fa-check-square"></i> <span class="button-text">Product Verification</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_sales_report.php' ; return false;"><i class="fas fa-chart-line"></i> <span class="button-text">Sales Report</span></button>
            <button class="navbar-button" onclick="window.location.href='admin_orders_report.php' ; return false;"><i class="fas fa-chart-bar"></i> <span class="button-text">Order Report</span></button>
            <button class="navbar-button"><i class="fas fa-file-invoice"></i> <span class="button-text">Invoices</span></button>
            <button class="navbar-button"><i class="fas fa-dollar-sign"></i> <span class="button-text">Sales</span></button>
            <button class="navbar-button"><i class="fas fa-shopping-bag"></i> <span class="button-text">Orders</span></button>
            <button class="navbar-button"><i class="fas fa-sign-out-alt"></i> <span class="button-text">Logout</span></button>
            <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
        </div>
</div>