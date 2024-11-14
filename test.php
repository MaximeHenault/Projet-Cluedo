<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Projet Cluedo</title>
        <meta charset= "utf-8">
        <link rel="stylesheet">
    </head>
    <body>
        <h1>Bienvenue sur le jeu</h1>
        <p>Choix du personnages :</p>

        <?php
            $bdd_fichier = 'cluedo.db';
            $sqlite = new SQLite3($bdd_fichier);
            $sql = 'SELECT nom_personnage FROM personnages';
            $requete = $sqlite->prepare($sql);
            $result = $requete->execute();
            echo "<ul>";
            while ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo '<li><a href="Hall.php?id=' . $adj['id_personnage'] . '">' . htmlspecialchars($adj['nom_personnage']) . '</a></li>';
            }
            echo "</ul>";
        ?>
        <a href="PageAcceuil.php">Retour à l'acceuil</a>
    </body>
</html>
