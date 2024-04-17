// JavaScript for image switching
const mainImage = document.getElementById('main_image');
const thumbnails = document.querySelectorAll('.thumbnail');

thumbnails.forEach((thumbnail, index) => {
    thumbnail.addEventListener('click', () => {
        const imageUrl = thumbnail.getAttribute('src');
        mainImage.setAttribute('src', imageUrl);
    });
    
    // Automatically load the first thumbnail image as the main image
    if (index === 0) {
        const imageUrl = thumbnail.getAttribute('src');
        mainImage.setAttribute('src', imageUrl);
    }
});

// Automatic image switching
let currentIndex = 0;

function switchImage() {
    const images = document.querySelectorAll('.thumbnail');
    currentIndex = (currentIndex + 1) % images.length;
    const imageUrl = images[currentIndex].getAttribute('src');
    mainImage.setAttribute('src', imageUrl);
}

setInterval(switchImage, 3000); // Change image every 3 seconds


document.addEventListener('DOMContentLoaded', function() {
    const navButtons = document.querySelectorAll('.nav_btn');
    const productInfo = document.querySelectorAll('.product_info');

    // Show the default product info section (Ingredients) and highlight its button
    const defaultSection = document.getElementById('ingredients_info');
    const defaultButton = document.querySelector('[data-target="ingredients"]');
    if (defaultSection && defaultButton) {
        defaultSection.style.display = 'block';
        defaultButton.classList.add('active');
        console.log('Default section (Ingredients) is set visible.');
    } else {
        console.error('Default section (Ingredients) or button not found.');
    }

    if (navButtons.length > 0 && productInfo.length > 0) {
        console.log('Event listeners added for navigation buttons.');
        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                console.log('Button clicked:', target); // Log clicked button

                // Show the clicked product info section and highlight its button
                productInfo.forEach(info => {
                    if (info.id === target + '_info') {
                        info.style.display = 'block';
                        console.log('Showing section:', info.id); // Log shown section
                    } else {
                        info.style.display = 'none';
                    }
                });

                // Highlight the clicked button and remove highlight from others
                navButtons.forEach(btn => {
                    if (btn === this) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            });
        });
    } else {
        console.error('Navigation buttons or product info sections not found.');
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const decreaseButton = document.getElementById('decrease_qty');
    const increaseButton = document.getElementById('increase_qty');
    const quantityInput = document.getElementById('quantity_input');

    // Decrease quantity by one
    decreaseButton.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    // Increase quantity by one
    increaseButton.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });
});


document.addEventListener('DOMContentLoaded', function() {
    function calculateSlidesPerView() {
        if (window.innerWidth < 768) { // Mobile devices
            return 1; // Display one slide per view
        } else { // Tablets and desktops
            return 2; // Display two slides per view
        }
    }

    // Initialize Swiper
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: calculateSlidesPerView(),
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Function to stop Swiper autoplay for 5 minutes
    function stopAutoplayForFiveMinutes() {
        swiper.autoplay.stop();
        setTimeout(function() {
            swiper.autoplay.start();
        }, 300000); // 5 minutes in milliseconds
    }

    // Show reply form when reply button is clicked
    var replyBtns = document.querySelectorAll('.reply-btn');
    replyBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = this.nextElementSibling;
            form.style.display = 'block';

            // Pause Swiper autoplay for 5 minutes
            stopAutoplayForFiveMinutes();
        });
    });
});
