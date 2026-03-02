<?php

$host     = 'db';
$dbname   = 'wishesDb';
$username = 'root';
$password = 'password';

$db = new mysqli($host, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $db->real_escape_string($_POST["message"]);
    $db->query("INSERT INTO wishes (message) VALUES ('$message')");
}
?>

<h2>Jätä toiveesi anonyymisti</h2>

<form method="POST">
    <textarea name="message" required></textarea><br>
    <button type="submit">Lähetä</button>
</form>

<h3>Toiveet:</h3>

<?php
$result = $db->query("SELECT * FROM wishes ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row["message"]) . "</p>";
}
?>
