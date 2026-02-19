<?php
require "../yhteys.php";

$action = $_GET["action"] ?? "list";

// Poistaminen
if ($action === "delete" && isset($_GET["id"])) {
    $id = (int)$_GET["id"];

    // haetaan kuvan nimi
    $stmt = $yhteys->prepare("SELECT image FROM news WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $rivi = $res->fetch_assoc();

    if ($rivi && !empty($rivi["image"])) {
        $polku = "../images/news/" . $rivi["image"];
        if (file_exists($polku)) unlink($polku);
    }

    // poistetaan uutinen
    $stmt = $yhteys->prepare("DELETE FROM news WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

    // Lisäys
if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $image = null;

    if (!empty($_FILES["image"]["name"])) {
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (in_array($ext, ["jpg","jpeg","png","webp"])) {
            $image = uniqid() . "." . $ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], "../images/news/" . $image);
        }
    }

    $stmt = $yhteys->prepare("INSERT INTO news (title, content, image) VALUES (?,?,?)");
    $stmt->bind_param("sss", $title, $content, $image);
    $stmt->execute();

    // aukaisee sivun uudelleen
    header("Location: index.php");
    exit;
}

// Muokkaus

if ($action === "edit" && isset($_GET["id"])) {
    $id = (int)$_GET["id"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = trim($_POST["title"]);
        $content = trim($_POST["content"]);
        $image = $_POST["old_image"];

        if (!empty($_FILES["image"]["name"])) {
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            if (in_array($ext, ["jpg","jpeg","png","webp"])) {
                if ($image && file_exists("../images/news/".$image)) {
                    unlink("../images/news/".$image);
                }
                $image = uniqid().".".$ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], "../images/news/".$image);
            }
        }

        $stmt = $yhteys->prepare("UPDATE news SET title=?, content=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $content, $image, $id);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }

    $stmt = $yhteys->prepare("SELECT * FROM news WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $uutinen = $stmt->get_result()->fetch_assoc();
}
?>
<!-- Html Osio -->

<!-- Nappi että voi palata pääsivulle kun on laittanut uuden uutien -->
<form action="../index.php">
    <button type="submit">Palaa asiakassivulle</button>
</form>

<!DOCTYPE html>
<html lang="fi">
<head>
<meta charset="UTF-8">
<title>Admin</title>
</head>
<body>


<h1>Admin – uutiset</h1>

<!-- Ottaa yhteyden listaan että voi tehdä muutoksia -->

<?php if ($action === "list"): ?>
<a href="?action=add">+ Lisää uutinen</a><hr>
<?php
$tulos = $yhteys->query("SELECT id,title FROM news ORDER BY created_at DESC");
while ($r = $tulos->fetch_assoc()):
?>
<p>
    <!-- Muokkaa ja poista napit -->
<?= htmlspecialchars($r["title"]) ?>
 | <a href="?action=edit&id=<?= $r["id"] ?>">Muokkaa</a>
    <!-- poistamisen vahvistus -->
 | <a href="?action=delete&id=<?= $r["id"] ?>" onclick="return confirm('Poistetaanko?');">Poista</a>
</p>
<?php endwhile; ?>
    <!-- "Lisää" nappi -->
<?php elseif ($action === "add"): ?>
<h2>Lisää uutinen</h2>
    <!-- Kuvan toimintaan vaadittava koodi (kysyin ai:lta miten sen saisi toimimaan) -->
<form method="post" enctype="multipart/form-data">
    <!-- Alue otsikolle -->
<input name="title" placeholder="Otsikko" required><br><br>
    <!-- Alue sisällölle -->
<textarea name="content" placeholder="Sisältö" required></textarea><br><br>
    <!-- Alue kuvalle -->
<input type="file" name="image"><br><br>
    <!-- Tallennus nappi -->
<button>Tallenna</button>
</form>
    <!-- Alue joka aukeaa kun painat muokkaa näppäintä -->
<?php elseif ($action === "edit"): ?>
<h2>Muokkaa uutista</h2>
    <!-- Muokkauksen tallennus -->
<form method="post" enctype="multipart/form-data">
    <!-- Näyttää vanhan otsikon ja sisällön -->
<input name="title" value="<?= htmlspecialchars($uutinen["title"]) ?>"><br><br>
<textarea name="content"><?= htmlspecialchars($uutinen["content"]) ?></textarea><br><br>
    <!-- jos uutisella on kuva niin se näytetään -->
<?php if ($uutinen["image"]): ?>
<img src="../images/news/<?= $uutinen["image"] ?>" style="max-width:200px"><br>
<?php endif; ?>
    <!-- piilotettu osio joka muistaa vanhan kuvan jos sitä ei vaihdeta -->
<input type="hidden" name="old_image" value="<?= $uutinen["image"] ?>">
<input type="file" name="image"><br><br>
 <!-- tallenna muutokset -->
<button>Päivitä</button>
</form>
<?php endif; ?>

</body>
</html>
