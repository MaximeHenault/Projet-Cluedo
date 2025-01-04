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
<div class="container">
    <?php
    // Regarde si la salle est dans le titre, si oui l'affecte à une variable pièce
    if (isset($_GET['salle'])) {
        $piece = htmlspecialchars($_GET['salle']);
        // Création d'un tableau associatif qui relie le titre à l'image
        $pieces_images = [
            "Hall" => "Hall.png",
            "Salle à manger" => "Salle%20à%20manger.png",
            "Salle de billard" => "Salle%20de%20billard.png",
            "Cuisine" => "Cuisine.png",
            "Salon" => "Salon.png",
            "Véranda" => "Véranda.png",
            "Bibliothèque" => "Bibliothèque.png",
            "Bureau" => "Bureau.png"
        ];

        // Attribue l'image en fonction du lieu dans l'URL
        if (array_key_exists($piece, $pieces_images)) {
            echo '<div class="image-container">';
            echo '<img src="/images/' . $pieces_images[$piece] . '" alt="' . $piece . '">';
            echo '</div>';
        }
    }
    ?>

    <!-- Création de la classe pour le texte -->
    <div class="text-overlay">
        <?php
        // Vérifie si la salle a déjà soumis une hypothèse, sinon affecte une valeur nulle
        if (!isset($_SESSION['derniere_salle'])) {
            $_SESSION['derniere_salle'] = '';
        }

        // Récupère la salle dans l'URL et l'affiche avec l'article approprié
        if (isset($_GET['salle'])) {
            $piece = $_GET['salle'];
            if (in_array($piece, ["Véranda", "Bibliothèque", "Salle à manger", "Cuisine", "Salle de billard"])) {
                echo '<h1>Vous êtes à la ' . $piece . '</h1>';
            } else {
                echo '<h1>Vous êtes au ' . $piece . '</h1>';
            }
        }

        // Connexion à la base de données
        $bdd_fichier = 'cluedo.db';
        $sqlite = new SQLite3($bdd_fichier);

        // Récupère l'ID dans l'URL et affiche le personnage associé
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $requete->execute();
            if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<h2>Vous avez choisi : " . htmlspecialchars($adj['nom_personnage']) . "</h2>";
            }
        }

        // Crée les personnages, armes et pièces aléatoires
        $_SESSION['personnagealeatoire'] = $_SESSION['personnagealeatoire'] ?? rand(1, 6);
        $_SESSION['armealeatoire'] = $_SESSION['armealeatoire'] ?? rand(1, 6);
        $_SESSION['piecealeatoire'] = $_SESSION['piecealeatoire'] ?? rand(1, 8);

        // Les crée qu'une seule fois par session
        $personnagealeatoire = $_SESSION['personnagealeatoire'];
        $armealeatoire = $_SESSION['armealeatoire'];
        $piecealeatoire = $_SESSION['piecealeatoire'];
        ?>

        <form method="POST">
            <?php
            // Fonction qui affiche les menus déroulants pour les hypothèses
            function afficherMenuDeroulant($sqlite, $table, $colonne, $id_colonne, $label) {
                $sql = "SELECT $colonne, $id_colonne FROM $table";
                $result = $sqlite->query($sql);
                echo '<label for="' . $table . '" class="label-spacing">' . " " . $label . " " . '</label>';
                echo '<select name="' . $table . '" id="' . $table . '">';
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo '<option value="' . $row[$id_colonne] . '">' . htmlspecialchars($row[$colonne]) . '</option>';
                }
                echo '</select>';
            }

            afficherMenuDeroulant($sqlite, 'personnages', 'nom_personnage', 'id_personnage', "Je pense que c'est");
            afficherMenuDeroulant($sqlite, 'armes', 'nom_arme', 'id_arme', "avec");
            afficherMenuDeroulant($sqlite, 'pieces', 'nom_piece', 'id_piece', "dans");
            ?>

            <br><br>
            <button type="submit" name="dejademande">Valider</button>
        </form>
        <br>

        <?php
        //Vérifie si la dernière pièce n'est pas la même que actuellement (Si une hypothèse à déja était soumise)
        if ($_SESSION['derniere_salle'] != $piece) {
            //Récupère l'id des personnage, arme et pièce que le joueur doit trouver
            $personnage_id = $_POST['personnages'] ?? null;
            $arme_id = $_POST['armes'] ?? null;
            $piece_id = $_POST['pieces'] ?? null;

            //Récupère le nom associé à l'id
            $sql = 'SELECT nom_piece FROM pieces WHERE id_piece = :id';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':id', $piece_id, SQLITE3_INTEGER);
            $result = $requete->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $nom_piece = $row['nom_piece'];
            }

            //Récupère le nom associé à l'id
            $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':id', $personnage_id, SQLITE3_INTEGER);
            $result = $requete->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $nom_personnage = $row['nom_personnage'];
            }

            //Récupère le nom associé à l'id
            $sql = 'SELECT nom_arme FROM armes WHERE id_arme = :id';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':id', $arme_id, SQLITE3_INTEGER);
            $result = $requete->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $nom_arme = $row['nom_arme'];
            }

            // Validation de l'hypothèse
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Affecte une certaine valeur en fonction de ce qui est bon dans l'hypothèse et retourne un chiffre si une des 3 hypothèses est fausses aléatoirement
                if ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['armealeatoire'] == $arme_id && $_SESSION['piecealeatoire'] == $piece_id) 
                {
                    $reponse = 0;
                } 
                elseif ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['armealeatoire'] == $arme_id) 
                {
                    $reponse = 3;
                } 
                elseif ($_SESSION['armealeatoire'] == $arme_id && $_SESSION['piecealeatoire'] == $piece_id) 
                {
                    $reponse = 1;
                } 
                elseif ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['piecealeatoire'] == $piece_id) 
                {
                    $reponse = 2;
                } 
                elseif ($_SESSION['personnagealeatoire'] == $personnage_id) 
                {
                    $reponse = rand(2, 3);
                } 
                elseif ($_SESSION['armealeatoire'] == $arme_id) 
                {
                    $reponse = (rand(0, 1) === 0) ? 1 : 3;
                } 
                elseif ($_SESSION['piecealeatoire'] == $piece_id) 
                {
                    $reponse = rand(1, 2);
                } 
                else
                {
                    $reponse = rand(1, 3);
                }
        
                //En fonction du chiffre re,voyés au dessus renvoie une certaine phrase
                if ($reponse == 0) {
                    echo "<p>Le criminel : $nom_personnage. L'arme : $nom_arme. La pièce : $nom_piece.</p>";
                } elseif ($reponse == 1) {
                    echo "<p>Ce n'est pas $nom_personnage qui a commis le crime.</p>";
                } elseif ($reponse == 2) {
                    if (in_array($nom_arme, ["Couteau", "Revolver"])) {
                        echo "<p>Il semble que la personne ne se soit pas fait tuer avec le $nom_arme.</p>";
                    } else {
                        echo "<p>Il semble que la personne ne se soit pas fait tuer avec la $nom_arme.</p>";
                    }
                } elseif ($reponse == 3) {
                    if (in_array($nom_piece, ["Véranda", "Bibliothèque", "Salle à manger", "Cuisine", "Salle de billard"])) {
                        echo "<p>La $nom_piece n'est pas la bonne pièce, essayez en une autre.</p>";
                    } else {
                        echo "<p>Le $nom_piece n'est pas la bonne pièce, essayez en une autre.</p>";
                    }
                }
        
                //Met à jour la variable pour savoir la salle de la dernière hypothèse
                $_SESSION['derniere_salle'] = $piece;
            }
        } 
        //L'hypothèse à deja était rentré dans cette pièce
        else {
            echo "<p>Vous avez déjà rentré une hypothèse dans cette salle.</p>";
        }
        ?>

        <br>

        <h2>Vous pouvez aller :</h2>
        <?php
        //Récupère la valeur de l'id dans l'url et fait une requête pour savoir les portes adjacentes, puis l'affiche sous une liste
        if (isset($_GET['salle'])) {
            $piece = $_GET['salle'];
            $sql = 'SELECT DISTINCT p2.nom_piece 
                    FROM pieces p1 
                    JOIN portes pr ON p1.id_piece = pr.id_piece1 OR p1.id_piece = pr.id_piece2 
                    JOIN pieces p2 ON (p2.id_piece = pr.id_piece1 AND p2.id_piece != p1.id_piece) 
                                    OR (p2.id_piece = pr.id_piece2 AND p2.id_piece != p1.id_piece) 
                    WHERE p1.nom_piece = :salle;';
            $requete = $sqlite->prepare($sql);
            $requete->bindValue(':salle', $piece, SQLITE3_TEXT);
            $result = $requete->execute();
            while ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
                echo '<li><a href="Hall.php?id=' . htmlspecialchars($id) . '&salle=' . htmlspecialchars($adj['nom_piece']) . '">' . htmlspecialchars($adj['nom_piece']) . '</a></li>';
            }
        }
        ?>

        <br>

        <!-- Bouton pour retourner à la première page -->
        <form method="POST">
            <button type="submit" name="quitter">Retour à l'accueil</button>
        </form>
        <?php
        //Détruit la session et renvoie vers la page Cluedo.php
        if (isset($_POST['quitter'])) {
            session_destroy();
            header("Location: Cluedo.php");
            exit();
        }
        ?>
    </div>
</div>
</body>
</html>
