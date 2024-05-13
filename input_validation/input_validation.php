<?php
// Function To validate Email For Uniqueness
function emailExists($email)
{
    include ("../connection\connection.php");

    // OCI prepared statement to check for duplicate email
    $sql_query = "SELECT * FROM users WHERE Email = :email";
    $stmt = oci_parse($conn, $sql_query);
    oci_bind_by_name($stmt, ":email", $email);
    oci_execute($stmt);

    $email_count = oci_fetch_all($stmt, $res);

    oci_free_statement($stmt);
    oci_close($conn);

    // Return true if email exists, false otherwise
    return $email_count > 0;
}

// Function to validate First Name 
function validateFirstName($first_name)
{
    // Check if first name is empty or doesn't match the pattern
    return !empty($first_name) && preg_match("/^[a-zA-Z'-]+$/", $first_name);
}

// Function To validate Last Name
function validateLastName($last_name)
{
    // Check if last name is empty or doesn't match the pattern
    return !empty($last_name) && preg_match("/^[a-zA-Z'-]+$/", $last_name);
}

// Function to validate email
function validateEmail($email)
{
    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate address
function validateAddress($address)
{
    return !empty($address);
}

// Function to validate contact number
function validateContactNumber($contact_number)
{
    return !empty($contact_number) && preg_match("/^[0-9]{10}$/", $contact_number);
}

// Function to validate password
function validatePassword($password)
{
    return !empty($password) && strlen($password) >= 6 && preg_match("/^(?=.[a-z])(?=.[A-Z])(?=.*\d).+$/", $password);
}

// Function to validate confirm password
function validateConfirmPassword($password, $confirm_password)
{
    return $password === $confirm_password;
}

// Function to validate date of birth
function validateDateOfBirth($dateOfBirth)
{
    // Check if the date is not empty and is a valid date in YYYY-MM-DD format
    if (!empty($dateOfBirth) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOfBirth)) {
        // Check if the date is a valid date
        $dateParts = explode('-', $dateOfBirth);
        if (count($dateParts) === 3 && checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
            return true;
        }
    }
    return false;
}

// Function to validate gender
function validateGender($gender)
{
    // Check if gender is not empty and is either 'Male', 'Female', or 'Other'
    return !empty($gender) && in_array($gender, ['Male', 'Female', 'Other']);
}

// Function to validate company registration number
function validateCompanyRegistrationNo($registrationNo)
{
    // Check if the registration number is not empty and consists of only alphanumeric characters
    return !empty($registrationNo) && ctype_alnum($registrationNo);
}

// Function to validate shop description
function validateShopDescription($description)
{
    // Check if the shop description is not empty
    return !empty($description);
}
?>