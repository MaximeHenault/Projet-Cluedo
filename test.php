<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Projet Cluedo</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Hall.css">
</head>
<body>
    <?php
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
        $requete = $sqlite->prepare($sql);
        $requete->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $requete->execute();

        if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<h2>Vous avez choisi : " . htmlspecialchars($adj['nom_personnage']) . "</h2>";
        }
    }

    if (isset($_GET['salle'])){
        $piece = $_GET['salle'];
        if ($piece == "Véranda" or $piece == "Bibliothèque" or $piece == "Salle à manger" or $piece == "Cuisine" or $piece == "Salle de billard"){
            echo '<h1>Vous êtes à la '. $piece .'</h1>';
        }
        else{
            echo '<h1>Vous êtes au '. $piece .'</h1>';
        }
            
            $sql = 'SELECT DISTINCT p2.nom_piece FROM pieces p1 JOIN portes pr ON p1.id_piece = pr.id_piece1 OR p1.id_piece = pr.id_piece2 JOIN pieces p2 ON (p2.id_piece = pr.id_piece1 AND p2.id_piece != p1.id_piece) OR (p2.id_piece = pr.id_piece2 AND p2.id_piece != p1.id_piece) WHERE p1.nom_piece = :salle;';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':salle', $piece, SQLITE3_TEXT);
            $result = $requete->execute();
            
            while ($adj = $result->fetchArray(SQLITE3_ASSOC)) {    
                echo '<li><a href="test.php?id=' . htmlspecialchars($id) . '&salle=' . htmlspecialchars($adj['nom_piece']) . '">' . htmlspecialchars($adj['nom_piece']) . '</a></li>';
            } 
    }    


    
    ?>

    <br>

    <!-- Bouton pour réinitialiser la session et revenir au menu -->
    <form method="POST">
        <button type="submit" name="quitter">Retour à l'accueil</button>
    </form>

    <?php
    // Si l'utilisateur clique sur "Retour à l'accueil", détruire la session
    if (isset($_POST['quitter'])) {
        session_destroy();  // Détruire la session
        header("Location: ChoixDuPersonnage.php");  // Rediriger vers la page d'accueil après avoir détruit la session
        exit();  // Assurer que le script s'arrête après la redirection
    }
    ?>

</body>
</html>
