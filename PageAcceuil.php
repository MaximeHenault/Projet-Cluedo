<!-- cd D:\HENAULT\ProjetCluedo  sqlite3 cluedo.db-->
<!-- cd D:\HENAULT\ProjetCluedo  php -S localhost:8000 -->

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Projet Cluedo</title>
        <meta charset= "utf-8">
        <link rel="stylesheet">
    </head>
    <body>
        <h1>Bienvenue sur le meilleur Cluedo du MONDE !!!</h1>
        <h2>Règles de base :</h2>
        <p>Le but du Cluedo est de découvrir qui a commis le meurtre, avec quelle arme et dans quelle pièce.
            Pour cela, vous pourrez faire des hypothèses dans chaque pièce que vous visiterez.
            À chaque mauvaise réponse, le jeu vous donnera une seule mauvaise indication,
            même si vous avez fait trois erreurs. Vous pourrez noter vos erreurs pour ne pas les refaire.
        </p>
        <button><a href = test.php>Lancer une partie</a></button>

        <h3>Inventaire :</h3>
        <?php 
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);
        $sql = 'SELECT * FROM armes';
        $requete = $sqlite -> prepare($sql);
        $result = $requete -> execute();
        echo "<ul>";
        while($adj = $result -> fetchArray(SQLITE3_ASSOC)) {
            echo '<li>'.$adj['nom_arme']."</li>";
        }
        echo "</ul>"
        ?>


        <h3>Le plan de la carte :</h3>
        <img src = "PlanCluedo.png" alt = "Le plan de la map" usemap = "#MapCluedo" height = 800>
        <map name = "MapCluedo">
        <area shape = "rect" coords = "0,0,570,170" href = Veranda.php alt = "" /> 
        <area shape = "rect" coords = "0,170,265,450" href = SalleDeBillard.php alt = "" /> 
        <area shape = "rect" coords = "0,450,265,800" href = Salon.php alt = "" /> 
        <area shape = "rect" coords = "265,170,455,345" href = Bureau.php alt = "" /> 
        <area shape = "rect" coords = "265,345,455,620" href = Bibliotheque.php alt = "" />
        <area shape = "rect" coords = "265,345,455,625" href = Bibliotheque.php alt = "" /> 
        <area shape = "rect" coords = "265,625,455,800" href = Hall.php alt = "" /> 
        <area shape = "rect" coords = "455,170,800,415" href = Cuisine.php alt = "" />
        <area shape = "rect" coords = "455, 415, 800,800" href = SalleAManger.php alt = "" />
        </map>

    </body>
</html>
