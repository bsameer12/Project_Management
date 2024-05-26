<nav class="navbar">
    <a href="trader_dashboard.php" class="logo"><img src="../logo.png" alt="logo" class="logo-image"></a>
    <h1 class="heading">Trader Dashboard</h1>
    <div class="user-info">
      <span class="welcome">Welcome,</span>
      <span class="trader-name"><?php echo $_SESSION["name"]; ?></span>
      <img src="../profile_image/<?php echo $_SESSION["picture"]; ?>" alt="Profile Image" class="profile-image">
      <div class="dropdown">
        <ul>
        <li><a href="trader_profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="trader_change_password.php"><i class="fas fa-lock"></i> Change Password</a></li>
        <li><a href="trader_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="sidebar">
  <div class="navbar-buttons">
  <button class="navbar-button" onclick="window.open('http://127.0.0.1:8080/apex/f?p=104:LOGIN_DESKTOP:15514426205272:::::', '_blank'); return false;">
    <i class="fas fa-tachometer-alt"></i> 
    <span class="button-text">Oracel Dashboard</span>
</button>
    <button class="navbar-button" onclick="window.location.href='trader_dashboard.php' ; return false;"><i class="fas fa-tachometer-alt"></i> <span class="button-text">Dashboard</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_products.php' ; return false;"><i class="fas fa-box"></i> <span class="button-text">Products</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_discount.php' ; return false;"><i class="fas fa-box"></i> <span class="button-text">Products Discounts</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_review.php' ; return false;"><i class="fas fa-star"></i> <span class="button-text">Reviews</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_order.php' ; return false;"><i class="fas fa-clipboard-list"></i> <span class="button-text">Orders</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_sales_report.php' ; return false;"><i class="fas fa-chart-line"></i> <span class="button-text">Sales Report</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_shop.php' ; return false;"><i class="fas fa-store"></i> <span class="button-text">Shop</span></button>
    <button class="navbar-button" onclick="window.location.href='trader_logout.php' ; return false;"><i class="fas fa-sign-out-alt"></i> <span class="button-text">Logout</span></button>
    <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
  </div>
</div>
