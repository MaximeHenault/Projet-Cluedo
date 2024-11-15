<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Projet Cluedo</title>
        <meta charset="utf-8">
        <link rel="stylesheet">
    </head>
    <body>
    <?php
    if (isset($_GET['id'])) {
        // Récupérer et sécuriser l'ID depuis l'URL
        $id = intval($_GET['id']);

        // Chemin vers la base de données SQLite
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        // Préparer une requête sécurisée
        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
        $requete = $sqlite->prepare($sql); // Initialiser $requete
        $requete->bindValue(':id', $id, SQLITE3_INTEGER);

        // Exécuter la requête
        $result = $requete->execute();

        // Afficher le résultat s'il existe
        if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<h1>Vous avez choisi : " . $adj['nom_personnage'] . "</h1>";
        }

        $result->finalize();
        $sqlite->close();
    }
    ?>
    <a href="test.php">Retour à test</a>
    </body>
</html>
