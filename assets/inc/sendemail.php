<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Sanitize input
$name    = trim(strip_tags($_POST['name'] ?? ''));
$email   = trim($_POST['Email'] ?? '');
$phone   = trim(strip_tags($_POST['Phone'] ?? ''));
$service = trim(strip_tags($_POST['service'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validate
if ($name === '' || $message === '') {
    echo json_encode(['success' => false, 'message' => 'Name and message are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email.']);
    exit;
}

// Prevent header injection
$name = str_replace(["\r", "\n"], ' ', $name);

$to      = 'amittrivedi.1392@gmail.com';
$subject = 'New Enquiry from ' . $name;

$body  = "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Service: $service\n\n";
$body .= "Message:\n$message\n";

$headers  = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Unable to send email. Please try again later.']);
}