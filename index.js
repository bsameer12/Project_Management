document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper(".home-slider", {
        spaceBetween: 120,
        centeredSlides: true,
        autoplay: {
            delay: 7500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true,
        speed: 10000,
        slidesPerView: 1, // Limit the number of visible slides
    });
});


    document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 10,
        centeredSlides: false,
        autoplay: {
            delay: 500,
            disableOnInteraction: true,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true,
        speed: 5000,
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            640: {
            slidesPerView: 2,
            },
            768: {
            slidesPerView: 2,
            },
            1024: {
            slidesPerView: 2,
            },
        },
    });
});

    var swiper = new Swiper(".review-slider", {
        spaceBetween: 10,
        centeredSlides: false,
        autoplay: {
            delay: 500,
            disableOnInteraction: true,
        },
        loop: true,
        speed: 5000,
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 2,
            },
        },
    });
    