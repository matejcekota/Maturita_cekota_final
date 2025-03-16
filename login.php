<?php
$servername = "localhost";
$username = "root";
$password = "databazematurita22"; 
$dbname = "motivace_registrace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $heslo = $conn->real_escape_string($_POST['heslo']);

    // SQL dotaz pro zjištění uživatele podle emailu
    $sql = "SELECT * FROM registrace WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Porovnání zadaného hesla s heslem uloženým v databázi (bez hashování)
        if ($heslo === $row['heslo']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Přesměrování na index.html po úspěšném přihlášení
            header("Location: index.html");
            exit();
        } else {
            echo "Špatné heslo!";
        }
    } else {
        echo "Uživatel s tímto emailem neexistuje!";
    }
}

$conn->close();
?>
