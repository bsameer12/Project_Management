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
    <script src="trader_dashboard.js"></script>
    
    <script src="trader_navbar.js"> </script>
</body>
</html>
