<?php
function generateRandomCode() {
    // Define the characters that can be used in the code (only numeric characters)
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $code = '';

    // Generate a random 6-character code
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }

    return $code;
}
?>