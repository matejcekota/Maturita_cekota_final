<?php
// Připojení k databázi
$servername = "localhost";
$username = "root"; // Zadejte vaše uživatelské jméno
$password = "databazematurita22"; // Zadejte vaše heslo
$dbname = "registrace_motivace";

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Zkontrolujte připojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jmeno = $conn->real_escape_string($_POST['jmeno']);
    $prijmeni = $conn->real_escape_string($_POST['prijmeni']);
    $email = $conn->real_escape_string($_POST['email']);
    $heslo = $conn->real_escape_string($_POST['heslo']);
    $pohlavi = $conn->real_escape_string($_POST['pohlavi']);

    // Hashování hesla
    $hashed_password = password_hash($heslo, PASSWORD_BCRYPT);

    // Kontrola, zda už email neexistuje
    $sql_check = "SELECT * FROM registrace WHERE email = '$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo "Email již existuje!";
    } else {
        // Vložení nového uživatele do databáze
        $sql = "INSERT INTO registrace (jmeno, prijmeni, pohlavi, email, heslo) 
                VALUES ('$jmeno', '$prijmeni', '$pohlavi', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "Úspěšně zaregistrováno!";
        } else {
            echo "Chyba: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>