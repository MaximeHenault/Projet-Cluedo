<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Projet Cluedo</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Hall.css">
</head>
<body>
    <h1>Bienvenue dans le Hall</h1>

    <?php
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
        $requete = $sqlite->prepare($sql);
        $requete->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $requete->execute();

        if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<h2>Vous avez choisi : " . htmlspecialchars($adj['nom_personnage']) . "</h2>";
        }
    }

    // Générer le personnage aléatoire (une seule fois par session)
    if (!isset($_SESSION['personnagealeatoire'])) {
        $_SESSION['personnagealeatoire'] = rand(1, 6);
    }
    $personnagealeatoire = $_SESSION['personnagealeatoire'];

    $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :personnagealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':personnagealeatoire', $personnagealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Personnage aléatoire : " . htmlspecialchars($adj['nom_personnage']) . "</p>";
    }
    ?>

    <form method="POST">
        <?php
        // Menu pour sélectionner un personnage
        $sql = 'SELECT nom_personnage, id_personnage FROM personnages';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="personnage">Je pense que c\'est </label>';
        echo '<select name="personnage" id="personnage">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_personnage'] . '">' . htmlspecialchars($row['nom_personnage']) . '</option>';
        }
        echo '</select>';
        ?>

        <button type="submit">Valider</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $personnage_id = isset($_POST['personnage']) ? (int)$_POST['personnage'] : null;

        echo "<p>Personnage sélectionné (ID) : " . $personnage_id . "</p>";
        echo "<p>Personnage aléatoire généré : " . $personnagealeatoire . "</p>";

        if ($_SESSION['personnagealeatoire'] == $personnage_id) {
            echo "<p>Bravo, vous avez deviné le bon personnage !</p>";
        } else {
            echo "<p>Désolé, ce n'est pas le bon personnage. Essayez à nouveau !</p>";
        }
    }
    ?>

    <a href="ChoixDuPersonnage.php">Retour à l'accueil</a>
</body>
</html>
