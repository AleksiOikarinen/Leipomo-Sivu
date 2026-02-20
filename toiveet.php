<?php

$conn = new mysqli("localhost", "kayttaja", "salasana", "tietokanta");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $conn->real_escape_string($_POST["message"]);
    $conn->query("INSERT INTO wishes (message) VALUES ('$message')");
}
?>

<h2>Jätä toiveesi anonyymisti</h2>

<form method="POST">
    <textarea name="message" required></textarea><br>
    <button type="submit">Lähetä</button>
</form>

<h3>Toiveet:</h3>

<?php
$result = $conn->query("SELECT * FROM wishes ORDER BY id DESC");

while($row = $result->fetch_assoc()) {
    echo "<p>" . $row["message"] . "</p>";
}
?>
