<?php
// Function to upload profile image
function uploadImage($path, $imageName)
{
    // Check if file is uploaded
    if (!isset($_FILES[$imageName]) || $_FILES[$imageName]['error'] === UPLOAD_ERR_NO_FILE) {
        return "No image selected! Please upload an image";
    }

    $file = $_FILES[$imageName];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $fileTemp = $file['tmp_name'];

    // Check if the file is an image
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    $fileInfo = pathinfo($fileName);
    $fileExtension = strtolower($fileInfo["extension"]);

    if (!in_array($fileExtension, $allowedFormats)) {
        return "Invalid image format! Please upload a valid image (jpg, jpeg, png, gif)";
    }

    // Check file size
    $maxSize = 5 * 1024 * 1024; // 5 MB
    if ($fileSize > $maxSize) {
        return "File size exceeds the maximum allowed limit (5MB)";
    }

    // Generate unique file name
    $uniqueFileName = uniqid() . '.' . $fileExtension;

    // Upload the file to the specified directory
    $uploadPath = $path . $uniqueFileName;

    if (move_uploaded_file($fileTemp, $uploadPath)) {
        return "Image uploaded successfully";
    } else {
        return "Failed to upload image";
    }
}
?>