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
        // Récupérer et sécuriser l'ID depuis l'URL
        $id = intval($_GET['id']);

        // Chemin vers la base de données SQLite
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        // Préparer une requête sécurisée pour récupérer le nom du personnage
        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
        $requete = $sqlite->prepare($sql);
        $requete->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $requete->execute();

        // Afficher le résultat s'il existe
        if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<h2>Vous avez choisi : " . htmlspecialchars($adj['nom_personnage']) . "</h2>";
        }

        // Créer l'arme aléatoire et l'afficher
        $armealeatoire = rand(1, 6);

        // Créer le personnage aléatoire et l'afficher
        $personnagealeatoire = rand(1, 6);
        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :personnagealeatoire';
        $requete = $sqlite->prepare($sql);
        $requete->bindValue(':personnagealeatoire', $personnagealeatoire, SQLITE3_INTEGER);
        $result = $requete->execute();

        if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<p>Personnage aléatoire : " . htmlspecialchars($adj['nom_personnage']) . "</p>";
        }

        // Créer la pièce aléatoire et l'afficher
        $piecealeatoire = rand(1, 8);
    }
    ?>

    <!-- Formulaire pour sélectionner les options -->
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

        echo '<label for="salle"> dans </label>';
        echo '<select name="salle" id="salle">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_piece'] . '">' . htmlspecialchars($row['nom_piece']) . '</option>';
        }
        echo '</select><br><br>';
        ?>

        <!-- Bouton pour soumettre le formulaire -->
        <button type="submit">Valider</button>
    </form>

    <?php
    // On vérifie si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les données envoyées par le formulaire
        $personnage_id = isset($_POST['personnage']) ? (int)$_POST['personnage'] : null;
        $arme_id = isset($_POST['arme']) ? (int)$_POST['arme'] : null;
        $salle_id = isset($_POST['salle']) ? (int)$_POST['salle'] : null;

        // Debugging : afficher les valeurs soumises
        echo "<p>Personnage sélectionné (ID) : " . $personnage_id . "</p>";
        echo "<p>Arme sélectionnée (ID) : " . $arme_id . "</p>";
        echo "<p>Salle sélectionnée (ID) : " . $salle_id . "</p>";
        echo "<p>Personnage aléatoire généré : " . $personnagealeatoire . "</p>";

        // Comparer l'ID du personnage sélectionné avec l'ID aléatoire
        if ($personnagealeatoire == $personnage_id) {
            echo "<p>Bravo, vous avez deviné le bon personnage !</p>";
        } else {
            echo "<p>Désolé, ce n'est pas le bon personnage. Essayez à nouveau !</p>";
        }

        // Vous pouvez ajouter ici la comparaison des armes et des salles, si nécessaire
    }
    ?>

    <a href="test.php">Retour à l'accueil</a>
</body>
</html>
