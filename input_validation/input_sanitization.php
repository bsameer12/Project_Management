<?php
// Function to sanitize first name
function sanitizeFirstName($firstName)
{
    return (string) filter_var($firstName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize last name
function sanitizeLastName($lastName)
{
    return (string) filter_var($lastName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize contact number
function sanitizeContactNumber($contactNumber)
{
    return (string) filter_var($contactNumber, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize address
function sanitizeAddress($address)
{
    return (string) filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize password (using MD5 for hashing)
function sanitizePassword($password)
{
    return (string) md5($password);
}

// Function to sanitize email
function sanitizeEmail($email)
{
    return (string) filter_var($email, FILTER_SANITIZE_EMAIL);
}

// Function to sanitize gender
function sanitizeGender($gender)
{
    return (string) filter_var($gender, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize date of birth
function sanitizeDOB($dob)
{
    // You may need to adjust the date format according to your needs
    return (string) date('Y-m-d', strtotime($dob));
}

// Function to sanitize shop name
function sanitizeShopName($shopName)
{
    return (string) filter_var($shopName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize company registration number
function sanitizeCompanyRegNo($regNo)
{
    return (string) filter_var($regNo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize shop description
function sanitizeShopDescription($description)
{
    return (string) filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize category
function sanitizeCategory($category)
{
    return (string) filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);;
}
?>
