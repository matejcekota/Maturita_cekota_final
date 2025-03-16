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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_type = $_POST['meeting_type'];
    $meeting_time = $_POST['meeting_time'];
    $branch_id = ($meeting_type == 'in_person') ? $_POST['branch_id'] : NULL;

    // Vložení rezervace
    if ($meeting_type == 'online') {
        // Pokud je schůzka online, není potřeba branch_id
        $sql = "INSERT INTO appointments (user_id, meeting_type, meeting_time) 
                VALUES ('" . $_SESSION['user_id'] . "', '$meeting_type', '$meeting_time')";
    } else {
        // Pokud je schůzka osobní, vložíme i branch_id
        $sql = "INSERT INTO appointments (user_id, meeting_type, branch_id, meeting_time) 
                VALUES ('" . $_SESSION['user_id'] . "', '$meeting_type', '$branch_id', '$meeting_time')";
    }

    if ($conn->query($sql) === TRUE) {
        // Nastavení session proměnné pro úspěšnou zprávu
        $_SESSION['message'] = "Schůzka byla úspěšně rezervována!";
        
        // Přesměrování na stránku my_appointments.php
        header("Location: my_appointments.php");
        exit();  // Ukončí skript, aby se přesměrování provedlo správně
    } else {
        $_SESSION['message'] = "Chyba při rezervaci schůzky: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Rezervace Schůzky</title>
    <link rel="stylesheet" href="css/appointments.css">
</head>
<body>

<div class="appointment-container">
    <div class="appointment-form-container">
        <div class="appointment-title">
            <span>Rezervace Schůzky</span>
        </div>
        <h1>Vyberte typ schůzky</h1>
        <form action="appointments.php" method="POST">
            <label for="online">Online schůzka</label>
            <input type="radio" name="meeting_type" value="online" required>
            <br>
            <label for="in_person">Osobní schůzka</label>
            <input type="radio" name="meeting_type" value="in_person" required>
            <br><br>

            <div id="branch-selection" style="display:none;">
                <label for="branch_id">Vyberte pobočku:</label>
                <select name="branch_id" id="branch_id">
                    <?php
                    // Načtení poboček z databáze
                    $sql_branches = "SELECT * FROM branches";
                    $result_branches = $conn->query($sql_branches);
                    while ($row = $result_branches->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . " - " . $row['address'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <br>
            <label for="meeting_time">Vyberte čas:</label>
            <input type="datetime-local" name="meeting_time" required>
            <br><br>
            <button type="submit">Rezervovat schůzku</button>
        </form>
    </div>
</div>

<script>
    // Zobrazení výběru pobočky pouze pokud je vybraná osobní schůzka
    const radioButtons = document.querySelectorAll('input[name="meeting_type"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', () => {
            if (document.querySelector('input[name="meeting_type"]:checked').value === 'in_person') {
                document.getElementById('branch-selection').style.display = 'block';
            } else {
                document.getElementById('branch-selection').style.display = 'none';
            }
        });
    });
</script>
</body>
</html>

