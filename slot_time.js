document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.pickup-slot-button');
    const contents = document.querySelectorAll('.slot-content');

    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            console.log('Date:', date);

            // Remove 'active' class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            // Add 'active' class to the clicked button
            this.classList.add('active');
            // Show the slot content corresponding to the clicked button
            const slotContent = document.getElementById(`slot-content-${date}`);
            console.log('Slot Content:', slotContent);
            contents.forEach(content => content.classList.remove('active'));
            if (slotContent) {
                slotContent.classList.add('active');
            }
        });
    });
});
