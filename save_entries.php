<?php
// Set the content type to JSON so the JavaScript client knows how to read the response.
header('Content-Type: application/json');

// The file where the entries will be saved.
$file_path = 'entries.txt';

// Check if the script received a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are accepted.']);
    exit;
}

// 1. Check if the 'entries' data field exists in the POST request
if (!isset($_POST['entries'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'No entries data received.']);
    exit;
}

$entries_data = $_POST['entries'];

// 2. Sanitize/clean the data before saving (basic cleanup for text)
$entries_data = trim($entries_data);

// 3. Attempt to write the data to the file
// LOCK_EX flag prevents other processes from writing to the file at the same time.
$result = @file_put_contents($file_path, $entries_data, LOCK_EX);

if ($result !== false) {
    // Success: file_put_contents returns the number of bytes written
    http_response_code(200); // OK
    echo json_encode(['status' => 'success', 'message' => 'Entries saved successfully to ' . $file_path]);
} else {
    // Failure: file_put_contents returns false on error (usually permission issues)
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Failed to write to file. Check server permissions on ' . $file_path]);
}
?>
