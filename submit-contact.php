<?php
// Set the response header to JSON format
header('Content-Type: application/json');

// Check if the request is a POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email recipient
    $recipient = "tommy.caruso2118+lytle-landscape@gmail.com";
    
    // Collect and sanitize form data
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags(trim($_POST["phone"])));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

    // Validate required fields
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please fill in all required fields correctly."
        ]);
        exit;
    }

    // Email subject and headers
    $subject = "New Contact Form Submission from $name";
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Compose the email body
    $email_body = "You have received a new message from your website contact form:\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Phone: $phone\n";
    $email_body .= "Message:\n$message\n";

    // Send the email
    if (mail($recipient, $subject, $email_body, $headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Thank you! Your message has been sent."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "There was an issue sending your message. Please try again later."
        ]);
    }
} else {
    // Handle invalid request method
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
