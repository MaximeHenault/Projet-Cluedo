<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta charset="utf-8">
        <title>Projet Cluedo</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Cluedo</h1>

        <p>Choix du personnage :</p>

        <?php
        // Connexion à la base de données SQLite
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        // Requête pour récupérer le nom et l'ID des personnages
        $sql = 'SELECT nom_personnage, id_personnage FROM personnages';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        // Affichage de la liste des personnages avec liens
        echo "<ul>";
        while ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            // Création du lien avec l'ID du personnage dans l'URL
            echo '<li><a href="test.php?id=' . $adj['id_personnage'] . '">' . htmlspecialchars($adj['nom_personnage']) . '</a></li>';
        }
        echo "</ul>";

        // Fermeture de la connexion à la base de données
        $sqlite->close();
        ?>

        <!-- Lien de retour à l'accueil -->
        <a href="PageAcceuil.php">Retour à l'accueil</a>
    </body>
</html>
