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
                    <input type="text" placeholder="Search...">
                    <button type="submit">Search</button>
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

