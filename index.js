document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper(".home-slider", {
        spaceBetween: 30,
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
        slidesPerView: 1, // Limit the number of visible slides
    });
});


    document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 5500,
            disableOnInteraction: true,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            640: {
            slidesPerView: 1,
            },
            768: {
            slidesPerView: 1,
            },
            1024: {
            slidesPerView: 1,
            },
        },
    });

    // defining swiper for review swction
var swiper = new Swiper(".review-slider", {
    spaceBetween: 20,
    centeredSlides: true,
    autoplay: {
        delay: 5500,
        disableOnInteraction: true,
    },
    loop:true,
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        640: {
        slidesPerView: 1,
        },
        768: {
        slidesPerView: 1,
        },
        1024: {
        slidesPerView: 1,
        },
    },
    });
});
