<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost"; // Database host
$username = "root"; // Database username
$password = "databazematurita22"; // Database password
$dbname = "motivace_registrace"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Check if form data is received
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Get and sanitize form data
    $jmeno = isset($_POST['jmeno']) ? trim($_POST['jmeno']) : null;
    $prijmeni = isset($_POST['prijmeni']) ? trim($_POST['prijmeni']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $heslo = isset($_POST['heslo']) ? $_POST['heslo'] : null;
    $pohlavi = isset($_POST['pohlavi']) ? $_POST['pohlavi'] : null;

    // Check if any fields are empty
    if (empty($jmeno) || empty($prijmeni) || empty($email) || empty($heslo) || empty($pohlavi)) {
        die("Error: All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    if (!$stmt) {
        die("Prepare failed (email check): " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Error: Email already exists.");
    }
    $stmt->close();

    // Hash the password securely
    $hashed_heslo = password_hash($heslo, PASSWORD_DEFAULT);

    // Prepare SQL query to insert data
    $sql = "INSERT INTO users (jmeno, prijmeni, email, heslo, pohlavi) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed (insert): " . $conn->error);
    }

    // Bind parameters and execute query
    $stmt->bind_param("sssss", $jmeno, $prijmeni, $email, $hashed_heslo, $pohlavi);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to a success page
        header("Location: success.html");
        exit();
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>