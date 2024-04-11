// Ensure that the DOM content is loaded before executing JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Get all quantity input fields and buttons for all products
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const decrementButtons = document.querySelectorAll('.decrement');
    const incrementButtons = document.querySelectorAll('.increment');

    // Add event listeners to decrement buttons
    decrementButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const input = button.nextElementSibling;
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    // Add event listeners to increment buttons
    incrementButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const input = button.previousElementSibling;
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        });
    });
});