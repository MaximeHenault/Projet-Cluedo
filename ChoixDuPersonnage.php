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
        $sql = 'SELECT * FROM personnages';
        $requete = $sqlite -> prepare($sql);
        $result = $requete -> execute();
        echo "<ul>";
        while($adj = $result -> fetchArray(SQLITE3_ASSOC)) {
            echo '<li>'.$adj['nom_personnage']."</li>";
        }
        echo "</ul>"
        ?>

        <a href="PageAcceuil.php">Retour à l'acceuil</a>
    </body>
</html>
