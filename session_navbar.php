<?php
 if(isset($_POST["search"])){
     // Input Sanizatization 
     require("input_validation\input_sanitization.php");
    $search_text = isset($_POST["searchText"]) ? sanitizeFirstName($_POST["searchText"]) : "";;
    header("Location: search_page.php?value=" . urlencode($search_text)); // URL encode the search text
    exit();

 }
 ?>
 <header>
        <nav>
            <div class="container">
                <div class="logo"><img src="logo.png"></div>
                <div class="nav-links">
                    <ul>
                        <li class="highlight"><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="session_contactus.php">Contacts</a></li>
                        <li><a href="category.php">Category</a></li>
                    </ul>
                </div>
                <div class="search">
                <form method="POST" action="" namme="search_form" id="search_form">
                    <input type="text" name="searchText" placeholder="<?php if(isset($search_text)){echo $search_text ; } else { echo "Search...";}?>" id="searchText" required>
                    <input type="submit" value="Search" name="search" id="search">
                </form>
                </div>
                <div class="menu-toggle"><i class="fas fa-bars"></i></div>
                <div class="submenu">
                    <ul>
                        <li class="highlight"><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contactus.php">Contact Us</a></li>
                        <li><a href="category.php">Category</a></li>
                        
                    </ul>
                </div>
                <div class="icons">
                    <a href="wishlist.php" class="icon"><i class="fas fa-heart" ></i></a>
                    <a href="cart.php" class="icon"><i class="fas fa-shopping-cart"></i></a>
                    <div class="profile-icon">
                        <div class="profile-image">
                            <img src="profile_image/<?php echo $_SESSION["picture"]; ?>" alt="<?php echo $_SESSION["name"] ; ?>">
                        </div>
                        <div class="submenu-profile">
                            <ul>
                                <li><a href="customer.php">Profile</a></li>
                                <li><a href="change_password.php">Change Password</a></li>
                                <li><a href="session/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    
            </div>
        </nav>
    </header>

