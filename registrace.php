<?php
$servername = "localhost";
$username = "root";
$password = "databazematurita22"; 
$dbname = "motivace_registrace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jmeno = $conn->real_escape_string($_POST['jmeno']);
    $prijmeni = $conn->real_escape_string($_POST['prijmeni']);
    $email = $conn->real_escape_string($_POST['email']);
    $heslo = $conn->real_escape_string($_POST['heslo']);
    $pohlavi = $conn->real_escape_string($_POST['pohlavi']);

    // Kontrola, zda už email neexistuje
    $sql_check = "SELECT * FROM registrace WHERE email = '$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo "Email již existuje!";
    } else {
        // Pokud není email, uložíme uživatele
        $sql = "INSERT INTO registrace (jmeno, prijmeni, pohlavi, email, heslo) 
                VALUES ('$jmeno', '$prijmeni', '$pohlavi', '$email', '$heslo')";

        if ($conn->query($sql) === TRUE) {
            // Zobrazí zprávu o úspěšné registraci
            echo "Úspěšně zaregistrováno! Nyní budete přesměrováni na přihlašovací stránku.";
            
            // Po 3 sekundách přesměruje na login.html
            header("refresh:3;url=login.html");
            exit();
        } else {
            echo "Chyba: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>