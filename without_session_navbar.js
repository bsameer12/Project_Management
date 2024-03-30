document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const submenu = document.querySelector('.submenu');

    menuToggle.addEventListener('click', function() {
        submenu.classList.toggle('show');
    });
});
