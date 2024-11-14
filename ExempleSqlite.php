<?php

	//Documentation php pour sqlite : https://www.php.net/manual/en/book.sqlite3.php
	

	/* Paramètres */
	$bdd_fichier = 'cluedo.db';		//Fichier de la base de données
	$piece = 'hall';				//Pièce à utiliser
	

	$sqlite = new SQLite3($bdd_fichier);		//On ouvre le fichier de la base de données
	
	/* Instruction SQL pour récupérer la liste des pieces adjacentes à la pièce paramétrée */
	$sql = 'SELECT adj.id_piece, adj.nom_piece ';
	$sql .= 'FROM pieces INNER JOIN portes ON portes.id_piece1=pieces.id_piece OR portes.id_piece2=pieces.id_piece ';
	$sql .= 'INNER JOIN pieces AS adj ON portes.id_piece1=adj.id_piece OR portes.id_piece2=adj.id_piece ';
	$sql .= 'WHERE adj.id_piece!=pieces.id_piece AND pieces.nom_piece LIKE :piece';
	
	
	/* Préparation de la requete et de ses paramètres */
	$requete = $sqlite -> prepare($sql);	
	$requete -> bindValue(':piece', $piece, SQLITE3_TEXT);
	
	$result = $requete -> execute();	//Execution de la requête et récupération du résultat


	/* On génère et on affiche notre page HTML avec la liste de nos films */
	echo "<!DOCTYPE html>\n";		//On demande un saut de ligne avec \n, seulement avec " et pas '
	echo "<html lang=\"fr\"><head><meta charset=\"UTF-8\">\n";	//Avec " on est obligé d'échapper les " a afficher avec \
	echo "<title>Pièces adjacentes à $piece</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<h1>Pièces adjacentes à $piece</h1>\n";
	echo "<ul>";
	
	while($adj = $result -> fetchArray(SQLITE3_ASSOC)) {
		echo '<li>'.$adj['nom_piece']." (id : {$adj['id_piece']})</li>";
	}

	echo "</ul>";
	echo "</body>\n";
	echo "</html>\n";
	
	
	$sqlite -> close();			//On ferme bien le fichier de la base de données avant de terminer!
	
?>
