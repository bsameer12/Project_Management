document.addEventListener("DOMContentLoaded", function() {
    // Display the personal information section by default
    document.querySelector(".personal-info").classList.add("active");

    document.querySelectorAll(".nav-btn").forEach(function(btn) {
        btn.addEventListener("click", function() {
            console.log("Navigation button clicked:", btn.textContent);
            // Remove active class from all nav buttons
            document.querySelectorAll(".nav-btn").forEach(function(navBtn) {
                navBtn.classList.remove("active");
            });
            // Hide all sections
            document.querySelectorAll(".my-orders-table, .my-reviews-table, .personal-info").forEach(function(section) {
                section.classList.remove("active");
            });
            // Add active class to clicked button
            btn.classList.add("active");
            // Show the corresponding section
            if (btn.textContent === "Profile") {
                document.querySelector(".personal-info").classList.add("active");
            } else {
                const target = btn.textContent.toLowerCase().replace(/\s/g, "-");
                const targetSection = document.querySelector("." + target + "-table");
                if (targetSection) {
                    targetSection.classList.add("active");
                }
            }
        });
    });
});

