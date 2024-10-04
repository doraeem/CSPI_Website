<?php

// Database connection details
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "Contact_DB"; // Your desired database name

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

// Create the 'Messages' table if it doesn't exist
$createMessagesTable = "
CREATE TABLE IF NOT EXISTS Messages (
    MessageID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Message TEXT NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($createMessagesTable) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare and bind statement to insert data into 'Messages' table
    $stmt = $conn->prepare("INSERT INTO Messages (Name, Email, Message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query and provide feedback to the user
    if ($stmt->execute()) {
        echo "<script>alert('Message was sent successfully!'); window.location.href='contact.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='contact.html';</script>";
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
