<?php

$host = 'db';
$dbname = 'wishesDb';
$username = 'root';
$password = 'password';

$database = new mysqli($host, $username, $password, $dbname);

if ($database->connect_error) {
    die("Tietokantavirhe: " . $database->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];

    $stmt = $database->prepare("INSERT INTO wishes (message) VALUES (?)");
    $stmt->bind_param("s", $message);
    $stmt->execute();
}
?>

<h2>Jätä toiveesi anonyymisti</h2>

<form method="POST" action="">
    <textarea name="message" required></textarea><br>
    <button type="submit">Lähetä</button>
</form>

<h3>Toiveet:</h3>

<?php
$result = $database->query("SELECT * FROM wishes ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row["message"]) . "</p>";
}
?>
