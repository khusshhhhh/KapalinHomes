<?php
// Database configuration
$servername = "localhost"; // Replace with your server hostname
$username = "khush"; // Replace with your DB username
$password = "Khush@3160"; // Replace with your DB password
$database = "kapalin_homes_contact"; // Replace with your DB name

// Email notification settings
$recipient_email = "workforkhush8@gmail.com"; // Replace with your email address
$email_subject = "Kapalin Hoems - New Contact Form Submission";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check database connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form inputs
    $name = $conn->real_escape_string($_POST['form_name']);
    $email = $conn->real_escape_string($_POST['form_email']);
    $phone = $conn->real_escape_string($_POST['form_phone']);
    $subject = $conn->real_escape_string($_POST['form_subject']);
    $message = $conn->real_escape_string($_POST['form_message']);

    // Insert form data into the database
    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Prepare email content
        $email_body = "You have received a new contact form submission:\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n";
        $email_body .= "Subject: $subject\n";
        $email_body .= "Message:\n$message\n";

        // Send email notification
        $headers = "From: noreply@yourdomain.com\r\n"; // Replace with your domain email
        $headers .= "Reply-To: $email\r\n"; // Optional: allows replying to the sender

        if (mail($recipient_email, $email_subject, $email_body, $headers)) {
            echo json_encode([
                "status" => "success",
                "message" => "Message successfully sent and email notification delivered."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Message saved, but email notification failed to send."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error saving message: " . $conn->error
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}

// Close database connection
$conn->close();
?>
