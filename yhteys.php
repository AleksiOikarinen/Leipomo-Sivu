<?php
$yhteys = new mysqli("localhost", "aleksi23007", "LyomSaq0", "wp_aleksi23007");

if ($yhteys->connect_error) {
    die("Tietokanatvirhe: " . $yhteys->connect_error);
}

$yhteys->set_charset("utf8mb4");
