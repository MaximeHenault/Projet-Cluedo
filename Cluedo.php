<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluedo</title>
    <link rel="stylesheet" href="Cluedo.css">
</head>
<body>

<!-- Div de la 1ère fenêtre, pour les règles du jeu (On utilise display en CSS pour afficher ou non la page)-->
<div id="Accueil">
    <h1>Cluedo</h1>

    <!-- Bouton pour lancer la partie (Utilise du JavaScript) -->
    <button id="startGameButton">Lancer la partie</button>
    
    <!-- Bouton qui affiche la fenêtre des règles du jeu -->
    <div class="button-container">
        <div class="clickable-box" id="openModal1">Règles du jeu</div>
    </div>
    
    <!-- Contenue de la fenêtre règle du jeu -->
    <div class="modal" id="modal1">
        <div class="modal-content">
            <h2>Règle du jeu</h2>
            <p>Bienvenue dans le manoir mystérieux où un crime a été commis ! Votre mission, résoudre l’affaire en découvrant qui est le coupable, quelle arme a été utilisée, et dans quelle pièce le meurtre a eu lieu.</p>
            <p>Comment jouer ?</p>
            <p>Explorez le manoir :</p>
            <p>Déplacez-vous de pièce en pièce pour enquêter, chaque pièce peut être la scène du crime, alors soyez attentif</p>
            <p>Formulez des hypothèses :</p>
            <p>Lorsque vous entrez dans une pièce, proposez une hypothèse en désignant :</p>
            <ul>
                <li>Un suspect (parmi les personnages du jeu).</li>
                <li>Une arme (couteau, chandelier, revolver, etc.).</li>
                <li>Une pièce (celle où vous vous trouvez).</li>
            </ul>
            <p>Analysez les indices:</p>
            <p>Si votre hypothèse est incorrecte, le jeu va vous fournir un indice. Mais attention : vous ne recevrez qu’une seule information à chaque fois, même si plusieurs éléments de votre hypothèse sont erronés.</p>
            <p>Notez vos découvertes :</p>
            <p>Utilisez votre carnet ou une feuille pour noter les indices collectés. Cela vous aidera à éliminer les mauvaises options et à affiner vos prochaines hypothèses.</p>
            <p>Le Cluedo est un jeu de déduction, de stratégie et d’observation. Plongez dans l’intrigue, et que le meilleur détective gagne !</p>
            <button class="close-btn" data-close="modal1">Fermer</button>
        </div>
    </div>
</div>


<!-- Div de la 2ème fenêtre, quand on clicque sur le bouton lancer la partie, sert a choisir le personnage -->
<div id="Personnage">
    <?php
    //Connection à la base et requête pour les noms et id des personnages.
    $bdd_fichier = 'cluedo.db';
    $sqlite = new SQLite3($bdd_fichier);

    $sql = 'SELECT nom_personnage, id_personnage FROM personnages';
    $requete = $sqlite->prepare($sql);
    $result = $requete->execute();

    //Récupère les noms et id des personnages.
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $nom_personnage = htmlspecialchars($row['nom_personnage']);
        $id_personnage = (int)$row['id_personnage'];

        //Affecte à la variable $texteperso un texte à chaque personnage en fonction de l'id.
        if ($id_personnage == 1) {
            $texteperso = "Mademoiselle Rose est une détective astucieuse, réputée pour sa capacité à résoudre les mystères les plus complexes. Son esprit vif et son regard perçant font d’elle une adversaire redoutable dans l’ombre de chaque enquête.";
        } elseif ($id_personnage == 2) {
            $texteperso = "Ancien militaire, le Colonel Moutarde est un homme de discipline et de stratégie. Bien qu'il semble réservé, son sens de la justice et ses secrets le rendent plus intrigant qu'il n'y paraît.";
        } elseif ($id_personnage == 3) {
            $texteperso = "Le Professeur Violet est un intellectuel mystérieux, passionné par les sciences et les théories complexes. Son esprit brillant cache parfois des idées qui frôlent la folie, mais il reste un maître de l’énigme.";
        } elseif ($id_personnage == 4) {
            $texteperso = "Élégante et influente, Madame Leblanc est une femme de lettres respectée dans les hautes sphères de la société. Sous sa beauté et son charme, elle cache une volonté de fer et une habileté à manœuvrer dans l’ombre.";
        } elseif ($id_personnage == 5) {
            $texteperso = "Monsieur Olive est un gentleman discret et mystérieux. Derrière son apparence soignée, il entretient des liens secrets et mène une vie dont on ne connaît pas tous les détails.";
        } elseif ($id_personnage == 6) {
            $texteperso = "Le Docteur Pervenche est un médecin respecté, mais ses recherches peu conventionnelles et son obsession pour la science révèlent une facette plus sombre et intrigante de sa personnalité.";
        }

        //Créer une classe pour chaque personnages (pour les manipuler en CSS) qui dirige vers la fenêtre de jeu.
        //Affecte l'image en fonction du nom du personnage, 2 classes sont crées l'une quand la souris passe sur la balise <a> (txtbtn-hover)
        //et l'autre classe (txtbtn-default) créer une petite case ou est marqué le nom du personnage
        echo '<a class="personnage personnage-' . $id_personnage . '" href="Hall.php?id=' . $id_personnage . '&salle=Hall">
            <div class="image-container">
                <img src=" /images/' . $nom_personnage . '.png" alt="' . $nom_personnage . '">
                <div class="txtbtn-hover">' . $texteperso . '</div>
                <div class="txtbtn-default">' . $nom_personnage . '</div>
            </div>
        </a>';
    }
    ?>
</div>

<script src="Cluedo.js"></script>

</body>
</html>
