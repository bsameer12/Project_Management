// trader_product.js
function openForm() {
    document.getElementById("productForm").style.display = "block";
}

function closeForm() {
    clearForm();
    document.getElementById("productForm").style.display = "none";
}

function clearForm() {
    const form = document.getElementById("productForms");
    form.reset();
    clearImagePreview();
}

function clearImagePreview() {
    const preview = document.getElementById('productImagePreview');
    preview.style.backgroundImage = 'none';
}

function previewProductImage() {
    const preview = document.getElementById('productImagePreview');
    const fileInput = document.getElementById('productImage');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.style.backgroundImage = `url('${e.target.result}')`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.backgroundImage = 'none';
    }
}
