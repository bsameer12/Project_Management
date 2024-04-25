document.addEventListener('DOMContentLoaded', function() {
    const userInfo = document.querySelector('.user-info');
    const dropdown = document.querySelector('.dropdown');

    // Toggle dropdown menu visibility when user info is clicked
    userInfo.addEventListener('click', function(event) {
    dropdown.classList.toggle('visible');
    event.stopPropagation(); // Prevent click event from propagating to document
    });

    // Close dropdown menu when clicked outside of user info or dropdown
    document.addEventListener('click', function(event) {
    const target = event.target;
    const isUserinfoClicked = userInfo.contains(target);
    const isDropdownClicked = dropdown.contains(target);

    if (!isUserinfoClicked && !isDropdownClicked) {
        dropdown.classList.remove('visible');
    }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const navbarButtons = document.querySelector('.navbar-buttons');

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

    // Check sidebar collapse when the page loads
    checkSidebarCollapse();

    // Check sidebar collapse on window resize
    window.addEventListener('resize', checkSidebarCollapse);
});    
