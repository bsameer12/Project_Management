<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HudderFoods</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="style.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
    require("navbar_switching.php");
    includeNavbarBasedOnSession();
    ?>
    
    <!-- Slider Section -->
    <section class="home" id="home">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- First slide -->
                <div class="swiper-slide slide" style="background-image: url('caviber_image.jpg');">
                    <div class="content">
                        <h3>Caviber</h3>
                        <p>Explore our delicious caviber dishes</p>
                        <button class="btn">Add to Cart</button>
                    </div>
                </div>
                <!-- Second slide -->
                <div class="swiper-slide slide" style="background-image: url('chese_image.jpg');">
                    <div class="content">
                        <h3>Cheese</h3>
                        <p>Indulge in our cheesy delights</p>
                        <button class="btn">Add to Cart</button>
                    </div>
                </div>
                <!-- Third slide -->
                <div class="swiper-slide slide" style="background-image: url('pork_image.jpeg');">
                    <div class="content">
                        <h3>Pork Steak</h3>
                        <p>Experience the taste of our savory pork steaks</p>
                        <button class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <section class="dishes" id="dishes">
    <!-- heading context section  -->
    <h1 class="heading"> Features Products </h1>
    <div class="box-container">
        <!-- creating first item  box   -->
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="caviber_image.jpg" alt="">
            <!-- item name -->
            <h3>Caviber</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Cheese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Itlian Cheeese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Japnese Cheese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>Indian Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>African Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>American Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item name -->
            <h3>Asian Pork Stake</h3>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
    </div>
    </section>

    <div class="container_dash">
    <div class="content">
        <h3>Selected UK Traders</h3>
        <h3>Freshly Picked </h3>
        <h3> Carefully Packaged </h3> 
        <h3>Ethical And Sustainable</h3>
    </div>
    </div>

    <section class="home" id="home">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- First slide -->
                <div class="swiper-slide slide" style="background-image: url('caviber_image.jpg');">
                </div>
                <!-- Second slide -->
                <div class="swiper-slide slide" style="background-image: url('chese_image.jpg');">
                </div>
            </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- review section starts here -->
<section class="review" id="review">
     <!-- Adding heading to section  -->
    <h3 class="sub-heading"> customer's review </h3>
    <h1 class="heading"> what they say </h1>
    <div class="swiper-container review-slider">

        <div class="swiper-wrapper">
            <!-- creating first comment box with slider effect   -->
            <div class="swiper-slide slide">
                <!-- favicon code for quote at right icon   -->
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <!-- linking images   -->
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Sabin Khanal</h3>
                        <div class="stars">
                            <!-- favicon code for star icon   -->
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Really nice place to hangout..</p>
            </div>
            <!-- creating second box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Shishir Acharya</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good place for a meeting. With good service</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Chadani Thapa</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>its nice place</p>
            </div>
            <!-- creating fourth box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Subu Basnet</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good & vintage entertainment, delicious food, reasonable price menu too must visit</p>
            </div>
            <!-- creating fifth box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Riya Shah</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good & vintage entertainment.</p>
            </div>


        </div>

    </div>
    
</section>
<!-- review section ends here -->

<!-- review section starts here -->
<section class="review" id="review">
     <!-- Adding heading to section  -->
    <h1 class="heading"> Meet Our Traders </h1>
    <div class="swiper-container review-slider">

        <div class="swiper-wrapper">
            <!-- creating first comment box with slider effect   -->
            <div class="swiper-slide slide">
                <!-- favicon code for quote at right icon   -->
                <div class="user">
                    <!-- linking images   -->
                    <img src="chese_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Cheesy World</h3>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Itlian cheese</p>
            </div>
            <!-- creating second box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <div class="user">
                    <img src="pork_image.jpeg" alt="">
                    <div class="user-info">
                        <h3>Butcher</h3>
                    </div>
                </div>
                <p>Golden stack</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <div class="user">
                    <img src="caviber_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Caviber World</h3>
                    </div>
                </div>
                <p>Best Place to get aunthetic Caviber</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
            <div class="user">
                    <!-- linking images   -->
                    <img src="chese_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Cheesy World</h3>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Itlian cheese</p>
            </div>
        </div>
    </div>
    
</section>
<!-- review section ends here -->


    <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>
