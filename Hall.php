<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Projet Cluedo</title>
        <meta charset= "utf-8">
        <link rel="stylesheet">
    </head>
    <body>
        <?php 
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);
        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = 1';
        $requete = $sqlite -> prepare($sql);
        $result = $requete -> execute();
        while($adj = $result -> fetchArray(SQLITE3_ASSOC)) {
            echo '<h1>Bonjour '.$adj['nom_personnage']."</h1>";
        }
        ?>

        <a href="PageAcceuil.php">Retour à l'acceuil</a>
    </body>
</html>
