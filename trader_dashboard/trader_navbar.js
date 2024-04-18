document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const navbarButtons = document.querySelector('.navbar-buttons');
    const profileInfo = document.querySelector('.profile-info');
    const dropdown = document.querySelector('.dropdown');

    // Function to check if the sidebar should be collapsed based on screen width
    function checkSidebarCollapse() {
        if (window.innerWidth <= 768) { // Adjust the threshold as needed
            sidebar.classList.add('collapsed');
            navbarButtons.classList.add('collapsed');
        }
    }

    // Toggle sidebar and navbar buttons when the toggle button is clicked
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        navbarButtons.classList.toggle('collapsed');
    });

    // Show/hide profile dropdown when profile info is clicked
    profileInfo.addEventListener('click', function() {
        dropdown.classList.toggle('active');
    });

    // Check sidebar collapse when the page loads
    checkSidebarCollapse();

    // Check sidebar collapse on window resize
    window.addEventListener('resize', checkSidebarCollapse);
});

