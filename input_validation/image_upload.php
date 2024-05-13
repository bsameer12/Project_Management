<?php
// Function to upload profile image
function uploadImage($path, $imageName)
{
    // Check if file is uploaded
    if (!isset($_FILES[$imageName]) || $_FILES[$imageName]['error'] === UPLOAD_ERR_NO_FILE) {
        return array("success" => 0, "fileName" => "", "message" => "No file uploaded.");
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
        return array("success" => 0, "fileName" => "", "message" => "Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.");
    }

    // Check file size
    $maxSize = 5 * 1024 * 1024; // 5 MB
    if ($fileSize > $maxSize) {
        return array("success" => 0, "fileName" => "", "message" => "File size exceeds maximum limit (5 MB).");
    }

    // Generate unique file name
    $uniqueFileName = uniqid() . '.' . $fileExtension;

    // Upload the file to the specified directory
    $uploadPath = $path . $uniqueFileName;

    if (move_uploaded_file($fileTemp, $uploadPath)) {
        return array("success" => 1, "fileName" => $uniqueFileName, "message" => "File uploaded successfully.");
    } else {
        return array("success" => 0, "fileName" => "", "message" => "Failed to upload file. Please try again.");
    }
}
?>