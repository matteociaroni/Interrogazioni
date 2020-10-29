<?php
include_once "util.php";
if(isUserAdmin())
{
	$classe=getUserClass();
	$id=$_GET["id"];
	$id_giorno=$_GET["giorno"];

	$query="SELECT id_alunno FROM interrogazioni_interrogati i
			JOIN interrogazioni_giorni AS g
			ON g.id=i.id_giorno
			JOIN interrogazioni_sessioni AS s
			ON s.id=g.id_sessione
			WHERE i.id='$id'
			AND s.classe='$classe'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	$alunno=$row["id_alunno"];

	$query="DELETE i FROM interrogazioni_interrogati i
			JOIN interrogazioni_giorni AS g
			ON g.id=i.id_giorno
			JOIN interrogazioni_sessioni AS s
			ON s.id=g.id_sessione
			WHERE i.id='$id'
			AND s.classe='$classe'";
	database()->query($query);

	$query="SELECT * FROM interrogazioni_interrogati
			WHERE id='$id'
			AND s.classe='$classe'";
	$risultato=database()->query($query);
	if($risultato->num_rows==0)
	{
		//invio notifica
		$link="http://acquamarinapesaro.altervista.org/Vari/interrogazioni/dettagli-giorno.php?id=".$id_giorno;
		$id_mittente=getUserId();
		$sql="INSERT INTO interrogazioni_notifiche (id_notifica, id_alunno, titolo, messaggio, data, link, id_mittente) VALUES (NULL, '$alunno', 'Rimosso', 'Sei stato rimosso dall interogazione', null, '$link', '$id_mittente')";
		database()->query($sql);
	}

	header("Location: modifica-interrogati.php?giorno=".$id_giorno);
}
else
{
	header("Location: index.php");
}