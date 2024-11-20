<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values from the form
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate inputs
    $errors = [];

    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    // If there are no errors, process the data
    if (empty($errors)) {
        // Send email or save to the database (example of saving to the database)
        try {
            // Database connection (update with your credentials)
            $host = 'localhost';
            $db = 'hausasoft';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($dsn, $user, $pass, $options);

            // Insert form data into the database
            $stmt = $pdo->prepare("INSERT INTO contact_form (first_name, last_name, email, message) VALUES (:first_name, :last_name, :email, :message)");
            $stmt->execute([
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':email' => $email,
                ':message' => $message,
            ]);

            // Success message
            echo "Thank you for contacting us! Your message has been successfully sent.";
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>
