<!-- Commandes pour démarrer le serveur local et ouvrir la base de données :
    cd D:\HENAULT\ProjetCluedo
    sqlite3 cluedo.db
    cd D:\HENAULT\ProjetCluedo
    php -S localhost:8000
-->

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta charset="utf-8">
        <title>Projet Cluedo</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Bienvenue sur le meilleur Cluedo du MONDE !!!</h1>

        <h2>Règles de base :</h2>
        <p>
            Le but du Cluedo est de découvrir qui a commis le meurtre, avec quelle arme et dans quelle pièce.
            Pour cela, vous pourrez faire des hypothèses dans chaque pièce que vous visiterez.
            À chaque mauvaise réponse, le jeu vous donnera une seule mauvaise indication,
            même si vous avez fait trois erreurs. Vous pourrez noter vos erreurs pour ne pas les refaire.
        </p>

        <button><a href="test.php">Lancer une partie</a></button>

        <h3>Inventaire :</h3>
        <?php 
        // Connexion à la base de données SQLite
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        // Requête pour récupérer la liste des armes
        $sql = 'SELECT * FROM armes';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        // Afficher les armes sous forme de liste
        echo "<ul>";
        while ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<li>' . htmlspecialchars($adj['nom_arme']) . '</li>';
        }
        echo "</ul>";

        // Fermeture de la connexion à la base de données
        $sqlite->close();
        ?>

        <h3>Le plan de la carte :</h3>
        <img src="PlanCluedo.png" alt="Le plan de la map" usemap="#MapCluedo" height="800">

        <!-- Carte interactive du Cluedo -->
        <map name="MapCluedo">
            <area shape="rect" coords="0,0,570,170" href="test.php" alt="Zone 1" />
            <area shape="rect" coords="0,170,265,450" href="test.php" alt="Zone 2" />
            <area shape="rect" coords="0,450,265,800" href="test.php" alt="Zone 3" />
            <area shape="rect" coords="265,170,455,345" href="test.php" alt="Zone 4" />
            <area shape="rect" coords="265,345,455,625" href="test.php" alt="Zone 5" />
            <area shape="rect" coords="265,625,455,800" href="test.php" alt="Zone 6" />
            <area shape="rect" coords="455,170,800,415" href="test.php" alt="Zone 7" />
            <area shape="rect" coords="455,415,800,800" href="test.php" alt="Zone 8" />
        </map>

    </body>
</html>
