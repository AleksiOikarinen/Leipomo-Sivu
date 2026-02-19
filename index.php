<?php
require "yhteys.php";
?>
<!DOCTYPE html>
<html lang="fi">
<!-- Leipomo -->
<head>
    <meta charset="UTF-8">
            <!-- CSS -->
    <link rel="stylesheet" href="Tyyli.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- HTML -->
    <header class="site-header">
        <div class="header-inner">
            <!-- Logo ja teksti -->
            <a href="index.php" class="logo" style="text-decoration:none;">
                <img src="logo2.png" alt="Leipomo logo">
                <div class="logo-text">
                    <h1>Leipomo</h1>
                    <span>Tuoretta joka päivä</span>
                </div>
            </a>

            <!-- Navigaatio -->
            <nav class="main-nav">
                <a href="tuotteet.html" style="color:#5a3a1a;">Hinnasto</a>
                <a href="yhteystiedot.html" style="color:#5a3a1a;">Yhteystiedot</a>
            </nav>

            <!-- Kielivalinta -->
            <div class="language">
                <img src="https://flagcdn.com/w20/se.png">
                <a href="Huvudsida.html" style="color:#5a3a1a">Kort på svenska</a>
            </div>
        </div>
    </header>

    <!-- MUUTETTAVA ALUE -->
    <main style="flex:1; max-width:1000px; margin:30px auto; padding:20px; background:white; border-radius:5px;">
        <h2 style="text-align:center; margin-bottom:40px;">
        Tervetuloa Leipomoon!
        </h2>

        <!-- Uutiset -->
        <h2>Ajankohtaista</h2>
        <!-- vie admin alueelle (suojattu .htaccessilla) -->
        <p style="text-align:right;">
            <a href="../Leipomo/admin/index.php"
               style="font-size:14px; color:#5a3a1a;">
             Hallinnoi uutisia
            </a>
        </p>
        <!-- Haetaan uutisten otsikko, sisältö, kuva ja luontipäivä -->
        <?php
        $sql = "SELECT title, content, image, created_at 
                FROM news 
                ORDER BY created_at DESC";
        /* tarkistaa onko tulos olemassa, jos ei niin virhe */
       $tulos = $yhteys->query($sql);

        if (!$tulos) {
        die("SQL-virhe: " . $yhteys->error);
        }
        // fetch_assoc() palauttaa uutiset taulukkona

        while ($rivi = $tulos->fetch_assoc()) {

            echo "<article style='margin-bottom:30px;'>";
            if (!empty($rivi["image"])) {
                /* Kuvan lisäys ja näyttäminen */
                echo "<img src='images/news/" . htmlspecialchars($rivi["image"]) . "' 
                           style='max-width:100%; margin:10px 0; border-radius:6px;'>";
            }
            /* Uutisten sisältö. nl2br pitää ribinvaihdot */
            echo "<h3>" . htmlspecialchars($rivi["title"]) . "</h3>";
            echo "<p>" . nl2br(htmlspecialchars($rivi["content"])) . "</p>";
            echo "<small>" . $rivi["created_at"] . "</small>";
            echo "</article>";
        }
        ?>

        <section style="display:flex; flex-direction:column; gap:30px;">
            <div style="display:flex; gap:20px; align-items:center;">
                <img src="Leipäkori.png" style="width:380px; border-radius:5px;">
                <div>
                    <h3>Vain tuoretta leipää</h3>
                    <p>Leivomme leipämme joka aamu perinteisiä reseptejä käyttäen.</p>
                </div>
            </div>

            <div style="display:flex; gap:20px; align-items:center;">
                <img src="Keksit.png" style="width:380px; border-radius:5px;">
                <div>
                    <h3>Makeat herkut</h3>
                    <p>Kaikki makeiset meiltä arkeen sekä juhlaan.</p>
                </div>
            </div>

            <div style="display:flex; gap:20px; align-items:center;">
                <img src="Leipomo.png" style="width:380px; border-radius:5px;">
                <div>
                    <h3>Paikallinen leipomo</h3>
                    <p>Olemme paikallinen perheleipomo, joka panostaa laatuun ja lohtuun.</p>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER ALUE -->
    <footer style="background:#222; color:white; padding:15px 30px;">
        <div style="max-width:1200px; margin:0 auto; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="logo2.png" style="width:50px;">
                <div>
                    <h2 style="margin: 0; font-size: 22px;">Leipomo</h2>
                    <span style="font-size:12px;">Tuoretta joka päivä</span>
                </div>
            </div>
            <div style="text-align:right;">
                <p style="margin:0;">Yrityksen nimi</p>
                <p style="margin:2px 0 0; font-size:13px; color: white;">
                    Katuosoite 1, 31300 Hämeenlinna<br>
                    Puh. 040 123 4567<br>
                    info@leipomo.fi
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
