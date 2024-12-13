<?php
session_start();  // Assurez-vous que la session est démarrée
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
    //Pour le choix du Personnage
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

    // Générer l'arme aléatoire (une seule fois par session)
    if (!isset($_SESSION['armealeatoire'])) {
        $_SESSION['armealeatoire'] = rand(1, 6);
    }
    $armealeatoire = $_SESSION['armealeatoire'];

    // Générer la salle aléatoire (une seule fois par session)
    if (!isset($_SESSION['piecealeatoire'])) {
        $_SESSION['piecealeatoire'] = rand(1, 8);
    }
    $piecealeatoire = $_SESSION['piecealeatoire'];

    //Tempo affiche perso
    $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :personnagealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':personnagealeatoire', $personnagealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Personnage aléatoire : " . htmlspecialchars($adj['nom_personnage']) . "</p>";
    }

    //Tempo affiche arme
    $sql = 'SELECT nom_arme FROM armes WHERE id_arme = :armealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':armealeatoire', $armealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Arme aléatoire : " . htmlspecialchars($adj['nom_arme']) . "</p>";
    }

    //Tempo affiche salle
    $sql = 'SELECT nom_piece FROM pieces WHERE id_piece = :piecealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':piecealeatoire', $piecealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Piece aléatoire : " . htmlspecialchars($adj['nom_piece']) . "</p>";
    }
    ?>


    <form method="POST">
        <!-- Menu déroulant pour les personnages -->
        <?php
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


        <!-- Menu déroulant pour les armes -->
        <?php
        $sql = 'SELECT nom_arme, id_arme FROM armes';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="arme"> avec </label>';
        echo '<select name="arme" id="arme">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_arme'] . '">' . htmlspecialchars($row['nom_arme']) . '</option>';
        }
        echo '</select>';
        ?>


        <!-- Menu déroulant pour les salles -->
        <?php
        $sql = 'SELECT nom_piece, id_piece FROM pieces';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="piece"> dans </label>';
        echo '<select name="piece" id="piece">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_piece'] . '">' . htmlspecialchars($row['nom_piece']) . '</option>';
        }
        echo '</select><br><br>';
        ?>


        <!-- Bouton pour soumettre le formulaire -->
        <button type="submit">Valider</button>
    </form>

    <?php
    // Méthode pour vérifier si l'utilisateur a la bonne réponse

    // Montre le personnage 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $personnage_id = isset($_POST['personnage']) ? (int)$_POST['personnage'] : null;

        echo "<p>Personnage sélectionné (ID) : " . $personnage_id . "</p>";
        echo "<p>Personnage aléatoire généré : " . $personnagealeatoire . "</p>";

        // Vérification de la réponse personnage
        if ($_SESSION['personnagealeatoire'] == $personnage_id) {
            echo "<p>Bravo, vous avez deviné le bon personnage !</p>";
        } else {
            echo "<p>Désolé, ce n'est pas le bon personnage. Essayez à nouveau !</p>";
        }
    }

    //Montre l'arme
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $arme_id = isset($_POST['arme']) ? (int)$_POST['arme'] : null;

        echo "<p>Arme sélectionné (ID) : " . $arme_id . "</p>";
        echo "<p>Arme aléatoire généré : " . $armealeatoire . "</p>";

        // Vérification de la réponse personnage
        if ($_SESSION['armealeatoire'] == $arme_id) {
            echo "<p>Bravo, vous avez deviné la bonne arme !</p>";
        } else {
            echo "<p>Désolé, ce n'est pas la bonne arme. Essayez à nouveau !</p>";
        }
    }

    //Montre la piece
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $piece_id = isset($_POST['piece']) ? (int)$_POST['piece'] : null;

        echo "<p>Piece sélectionné (ID) : " . $piece_id . "</p>";
        echo "<p>Piece aléatoire généré : " . $piecealeatoire . "</p>";

        // Vérification de la réponse personnage
        if ($_SESSION['piecealeatoire'] == $piece_id) {
            echo "<p>Bravo, vous avez deviné la bonne pièce !</p>";
        } else {
            echo "<p>Désolé, ce n'est pas la bonne pièce. Essayez à nouveau !</p>";
        }
    }
    ?>

    <br>
    <!-- Bouton pour réinitialiser la session et revenir au menu -->
    <form method="POST">
        <button type="submit" name="quitter">Retour à l'accueil</button>
    </form>

    <?php
    // Si l'utilisateur clique sur "Retour à l'accueil", détruire la session
    if (isset($_POST['quitter'])) {
        session_destroy();  // Détruire la session
        header("Location: ChoixDuPersonnage.php");  // Rediriger vers la page d'accueil après avoir détruit la session
        exit();  // Assurer que le script s'arrête après la redirection
    }
    ?>

</body>
</html>
