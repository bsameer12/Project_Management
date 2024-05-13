<?php
// Function to sanitize first name
function sanitizeFirstName($firstName)
{
    return filter_var($firstName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize username
function sanitizeUsername($username)
{
    return filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize last name
function sanitizeLastName($lastName)
{
    return filter_var($lastName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize contact number
function sanitizeContactNumber($contactNumber)
{
    return filter_var($contactNumber, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize address
function sanitizeAddress($address)
{
    return filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize password (using MD5 for hashing)
function sanitizePassword($password)
{
    return md5($password);
}

// Function to sanitize email
function sanitizeEmail($email)
{
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

// Function to sanitize gender
function sanitizeGender($gender)
{
    return filter_var($gender, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize date of birth
function sanitizeDOB($dob)
{
    // You may need to adjust the date format according to your needs
    return date('Y-m-d', strtotime($dob));
}

// Function to sanitize shop name
function sanitizeShopName($shopName)
{
    return filter_var($shopName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize company registration number
function sanitizeCompanyRegNo($regNo)
{
    return filter_var($regNo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Function to sanitize shop description
function sanitizeShopDescription($description)
{
    return filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}
?>