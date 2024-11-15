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

        // Créer le personnage aléatoire
        $personnage = rand(1, 6);

        // Créer l'arme aléatoire
        $arme = rand(1, 6);

        // Créer la pièce aléatoire
        $piece = rand(1, 8);

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

    <a href="test.php">Retour à l'accueil</a>
</body>
</html>
