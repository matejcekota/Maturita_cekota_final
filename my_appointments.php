<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "databazematurita22"; 
$dbname = "motivace_registrace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT appointments.*, branches.name AS branch_name, branches.address AS branch_address
        FROM appointments
        LEFT JOIN branches ON appointments.branch_id = branches.id
        WHERE appointments.user_id = '" . $_SESSION['user_id'] . "'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Moje Schůzky</title>
    <link rel="stylesheet" href="css/myappointments.css">
</head>
<body>

    <div class="container">
    <div class="login_container">
    <h1>Moje Schůzky</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Typ schůzky: " . $row['meeting_type'] . "</p>";
            if ($row['meeting_type'] == 'in_person') {
                echo "<p>Pobočka: " . $row['branch_name'] . " - " . $row['branch_address'] . "</p>";
            }
            echo "<p>Čas: " . $row['meeting_time'] . "</p>";
            echo "<hr>";
        }
    } else {
        echo "<p>Nemáte žádné rezervované schůzky.</p>";
    }
    ?>
</body>
</html>
