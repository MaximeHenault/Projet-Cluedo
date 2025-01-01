<?php
session_start(); // Assurez-vous que la session est démarrée
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Projet Cluedo</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Hall.css">
</head>
<body>
<div>
    <?php
    if (!isset($_SESSION['derniere_salle'])) {
        $_SESSION['derniere_salle'] = '';
    }

    if (isset($_GET['salle'])) {
        $piece = $_GET['salle'];
        if (in_array($piece, ["Véranda", "Bibliothèque", "Salle à manger", "Cuisine", "Salle de billard"])) {
            echo '<h1>Vous êtes à la ' . $piece . '</h1>';
        } else {
            echo '<h1>Vous êtes au ' . $piece . '</h1>';
        }
    }

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

    // Générer des données aléatoires (une seule fois par session)
    if (!isset($_SESSION['personnagealeatoire'])) {
        $_SESSION['personnagealeatoire'] = rand(1, 6);
    }
    if (!isset($_SESSION['armealeatoire'])) {
        $_SESSION['armealeatoire'] = rand(1, 6);
    }
    if (!isset($_SESSION['piecealeatoire'])) {
        $_SESSION['piecealeatoire'] = rand(1, 8);
    }

    $personnagealeatoire = $_SESSION['personnagealeatoire'];
    $armealeatoire = $_SESSION['armealeatoire'];
    $piecealeatoire = $_SESSION['piecealeatoire'];

    // Tempo affiche perso
    $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :personnagealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':personnagealeatoire', $personnagealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Personnage aléatoire : " . htmlspecialchars($adj['nom_personnage']) . "</p>";
    }

    // Tempo affiche arme
    $sql = 'SELECT nom_arme FROM armes WHERE id_arme = :armealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':armealeatoire', $armealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Arme aléatoire : " . htmlspecialchars($adj['nom_arme']) . "</p>";
    }

    // Tempo affiche salle
    $sql = 'SELECT nom_piece FROM pieces WHERE id_piece = :piecealeatoire';
    $requete = $sqlite->prepare($sql);
    $requete->bindValue(':piecealeatoire', $piecealeatoire, SQLITE3_INTEGER);
    $result = $requete->execute();

    if ($adj = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<p>Piece aléatoire : " . htmlspecialchars($adj['nom_piece']) . "</p>";
    }
    ?>

    <form method="POST">
        <!-- Menu déroulant pour les personnages -->
        <?php
        $sql = 'SELECT nom_personnage, id_personnage FROM personnages';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="personnage">Je pense que c\'est </label>';
        echo '<select name="personnage" id="personnage">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_personnage'] . '">' . htmlspecialchars($row['nom_personnage']) . '</option>';
        }
        echo '</select>';
        ?>

        <!-- Menu déroulant pour les armes -->
        <?php
        $sql = 'SELECT nom_arme, id_arme FROM armes';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="arme"> avec </label>';
        echo '<select name="arme" id="arme">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_arme'] . '">' . htmlspecialchars($row['nom_arme']) . '</option>';
        }
        echo '</select>';
        ?>

        <!-- Menu déroulant pour les salles -->
        <?php
        $sql = 'SELECT nom_piece, id_piece FROM pieces';
        $requete = $sqlite->prepare($sql);
        $result = $requete->execute();

        echo '<label for="piece"> dans </label>';
        echo '<select name="piece" id="piece">';
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo '<option value="' . $row['id_piece'] . '">' . htmlspecialchars($row['nom_piece']) . '</option>';
        }
        echo '</select><br><br>';
        ?>

        <!-- Bouton pour soumettre le formulaire -->
        <button type="submit" name="dejademande">Valider</button>
    </form>
    <br>

    <?php
    // Vérifie si le formulaire a été soumis et que les variables ont bien été définies
    if ($_SESSION['derniere_salle'] != $piece) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // On initialise les variables uniquement si elles sont définies dans le formulaire
            $personnage_id = isset($_POST['personnage']) ? (int)$_POST['personnage'] : null;
            $arme_id = isset($_POST['arme']) ? (int)$_POST['arme'] : null;
            $piece_id = isset($_POST['piece']) ? (int)$_POST['piece'] : null;

            // Connexion à la base de données
            $bdd_fichier = 'cluedo.db';
            $sqlite = new SQLite3($bdd_fichier);

            // Vérifie si toutes les réponses sont renseignées avant de comparer
            if ($personnage_id !== null && $arme_id !== null && $piece_id !== null) {
                // Récupération du nom de la pièce
                $sql = 'SELECT nom_piece FROM pieces WHERE id_piece = :id';
                $requete = $sqlite->prepare($sql);
                $requete->bindValue(':id', $piece_id, SQLITE3_INTEGER);
                $result = $requete->execute();
                if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $nom_piece = $row['nom_piece'];
                }

                // Récupération du nom du personnage
                $sql = 'SELECT nom_personnage FROM personnages WHERE id_personnage = :id';
                $requete = $sqlite->prepare($sql);
                $requete->bindValue(':id', $personnage_id, SQLITE3_INTEGER);
                $result = $requete->execute();
                if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $nom_personnage = $row['nom_personnage'];
                }

                // Récupération du nom de l'arme
                $sql = 'SELECT nom_arme FROM armes WHERE id_arme = :id';
                $requete = $sqlite->prepare($sql);
                $requete->bindValue(':id', $arme_id, SQLITE3_INTEGER);
                $result = $requete->execute();
                if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $nom_arme = $row['nom_arme'];
                }

                // Vérifie si l'utilisateur a trouvé la bonne combinaison
                if ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['armealeatoire'] == $arme_id && $_SESSION['piecealeatoire'] == $piece_id) {
                    $reponse = 0;
                } else if ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['armealeatoire'] == $arme_id) {
                    $reponse = 3;
                } else if ($_SESSION['armealeatoire'] == $arme_id && $_SESSION['piecealeatoire'] == $piece_id) {
                    $reponse = 1;
                } else if ($_SESSION['personnagealeatoire'] == $personnage_id && $_SESSION['piecealeatoire'] == $piece_id) {
                    $reponse = 2;
                } else if ($_SESSION['personnagealeatoire'] == $personnage_id) {
                    $reponse = rand(2, 3);
                } else if ($_SESSION['armealeatoire'] == $arme_id) {
                    $reponse = (rand(0, 1) === 0) ? 1 : 3;
                } else if ($_SESSION['piecealeatoire'] == $piece_id) {
                    $reponse = rand(1, 2);
                } else {
                    $reponse = rand(1, 3);
                }

                // Affichage des messages
                if ($reponse == 0) {
                    echo "Le criminel était $nom_personnage avec une $nom_arme dans le $nom_piece.";
                } else if ($reponse == 1) {
                    echo "Ce n'est pas le bon personnage. Vous avez choisi : $nom_personnage.";
                } else if ($reponse == 2) {
                    echo "Ce n'est pas la bonne arme. Vous avez choisi : $nom_arme.";
                } else if ($reponse == 3) {
                    echo "Ce n'est pas la bonne pièce. Vous avez choisi : $nom_piece.";
                }
            }
            $_SESSION['derniere_salle'] = $piece;
        }
    } else {
        echo "Vous avez déjà rentré une hypothèse dans cette salle";
    }
    ?>

    <br>

    <?php
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
    <!-- Bouton pour réinitialiser la session et revenir au menu -->
    <form method="POST">
        <button type="submit" name="quitter">Retour à l'accueil</button>
    </form>

    <?php
    if (isset($_POST['quitter'])) {
        session_destroy();
        header("Location: test.php");
        exit();
    }
    ?>
</div>
</body>
</html>
