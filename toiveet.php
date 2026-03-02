<?php

require "yhteys.php";
?>

<h2>Jätä toiveesi anonyymisti</h2>h2>

<form method="POST">
    <textarea name="message" required></textarea><br>
    <button type="submit">Lähetä</button>
</form>

<h3>Toiveet:</h3>

<?php
$result = $yhteys->query("SELECT * FROM wishes ORDER BY id DESC");

while($row = $result->fetch_assoc()) {
    echo "<p>" . $row["message"] . "</p>";
}
