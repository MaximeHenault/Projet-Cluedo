<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Projet Cluedo</title>
        <meta charset="utf-8">
        <link rel="stylesheet">
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
            $requete = $sqlite->prepare($sql); // Initialiser $requete
            $requete->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $requete->execute();

            // Afficher le résultat s'il existe
            if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<h2>Vous avez choisi : " . $adj['nom_personnage'] . "</h2>";
            }

            // Créer l'arme aléatoire et l'afficher
            $arme = rand(1, 6);
            $sql = 'SELECT nom_arme FROM armes WHERE id_arme = :arme';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':arme', $arme, SQLITE3_INTEGER);
            $result = $requete->execute();

            if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<p>Arme aléatoire : " . $adj['nom_arme'] . "</p>";
            }

            // Créer le personnage aléatoire et l'afficher 
            $personnage = rand(1, 6);  
            $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :personnage';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':personnage', $personnage, SQLITE3_INTEGER);
            $result = $requete->execute();

            if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<p>Personnage aléatoire : " . $adj['nom_personnage'] . "</p>";
            }

            // Créer la pièce aléatoire et l'afficher
            $piece = rand(1, 8);
            $sql = 'SELECT nom_piece FROM pieces WHERE id_piece = :piece';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':piece', $piece, SQLITE3_INTEGER);
            $result = $requete->execute();

            if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<p>Salle aléatoire : " . $adj['nom_piece'] . "</p>";
            }

            // Fermer la connexion à la base de données
            $sqlite->close();
        }
        ?>

        <a href="test.php">Retour à test</a>
    </body>
</html>
