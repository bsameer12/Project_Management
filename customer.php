<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="customer.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
</head>
<body>
    <?php
        include("session/session.php");
        include("session_navbar.php");
    ?>
    <div class="profile-container">
    <!-- Left side -->
    <div class="left-side">
        <div class="profile-picture">
            <!-- Placeholder for profile picture -->
            <img src="profile.jpg" alt="Profile Picture">
        </div>
        <button class="update-profile-btn">Update Profile Picture</button>
        <div class="navigation">
            <button class="nav-btn active">Profile</button>
            <button class="nav-btn">My Orders</button>
            <button class="nav-btn">My Reviews</button>
            <button class="nav-btn">Sign Out</button>
        </div>
    </div>
    
    <!-- Right side -->
    <div class="right-side">
    <div class="personal-info">
    <h2>Personal Information</h2>
    <form id="personal-info-form">
        <div class="form-row">
            <div class="input-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" required value="Sameer">
            </div>
            <div class="input-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" required value="Basnet">
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="bsameer22@tbc.edu.np">
            </div>
            <div class="input-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" required value="+9779824305625">
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required value="Kathmandu">
            </div>
            <div class="input-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required value="2003-10-12">
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <button type="submit" class="save-btn">Save</button>
            <button type="button" class="delete-account-btn">Delete Account</button>
        </div>
    </form>
</div>
        <!-- Dummy table for My Orders -->

    <div class="my-orders-table hidden">
    <h2>My Orders</h2>
        <table id="order_table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2024-04-12</td>
                    <td>Product 1, Product 2</td>
                    <td>$50.00</td>
                </tr>
                <!-- Add more rows as needed -->
                <tr>
                    <td>2</td>
                    <td>2024-04-22</td>
                    <td>Product 3, Product 4</td>
                    <td>$90.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>2024-11-12</td>
                    <td>Product 5, Product 2</td>
                    <td>$150.00</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>2023-04-12</td>
                    <td>Product 10, Product 12</td>
                    <td>$1150.00</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>2024-04-22</td>
                    <td>Product 12, Product 12</td>
                    <td>$950.00</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>2024-09-12</td>
                    <td>Product 5, Product 9</td>
                    <td>$9250.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Dummy table for My Reviews -->
    <div class="my-reviews-table hidden">
        <h2>My Reviews</h2>
        <table id="review_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product 1</td>
                    <td>5 stars</td>
                    <td>Great product!</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>
</div>
        <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="customer.js"></script>

    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#order_table, #review_table').DataTable({
                responsive: true
            });
        });
    </script>
</body>
</html>
