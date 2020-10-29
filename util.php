<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true)
{
	header('Location: login.php?next='.$_SERVER['REQUEST_URI']);
}
else
{
	$id_alunno=getUserId();
	$pagina=$_SERVER['REQUEST_URI'];
	$ip=$_SERVER['REMOTE_ADDR'];
	$sql="INSERT INTO interrogazioni_log (id, id_utente, pagina, data_inserimento) VALUES (NULL, '$id_alunno', '$pagina', NULL)";
	database()->query($sql);
}

function database()
{
	$username="acquamarinapesaro";
	$password="";
	$host="localhost";
	$database="my_acquamarinapesaro";
	$conn=new mysqli($host, $username, $password, $database);
	if($conn->connect_error)
	{
		die("Connection failed: ".$conn->connect_error);
	}
	return $conn;
}

function getUserName()
{
	$utente=$_SESSION['username'];
	$query="SELECT nome
    	FROM interrogazioni_alunni
        WHERE email='$utente'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["nome"];
}

function getUserSurname()
{
	$utente=$_SESSION['username'];
	$query="SELECT cognome
    	FROM interrogazioni_alunni
        WHERE email='$utente'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["cognome"];
}

function getUserEmail()
{
	return $_SESSION['username'];
}

function getUserClass()
{
	$utente=$_SESSION['username'];
	$query="SELECT classe
    	FROM interrogazioni_alunni
        WHERE email='$utente'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["classe"];
}

function getUserId()
{
	$utente=$_SESSION['username'];
	$query="SELECT id
    	FROM interrogazioni_alunni
        WHERE email='$utente'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["id"];
}

function isUserInterrogato($id_giorno)
{
	$id_alunno=getUserId();
	$classe=getUserClass();
	$query="SELECT i.id
    	FROM interrogazioni_interrogati AS i
        WHERE i.id_alunno='$id_alunno'
        AND i.id_giorno='$id_giorno'";
	$risultato=database()->query($query);
	return $risultato->num_rows;
}

function isGiornoFull($id)
{
	$classe=getUserClass();
	$query="SELECT COUNT(i.id) AS num, g.n_massimo AS max
    	FROM interrogazioni_interrogati AS i
    	JOIN interrogazioni_giorni AS g
    	ON g.id=i.id_giorno
    	JOIN interrogazioni_sessioni AS s
    	ON g.id_sessione=s.id
        WHERE  i.id_giorno='$id'
        AND s.classe='$classe'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	if(!$row["max"]>0 || $row["max"]>$row["num"])
	{
		return 0;
	}
	else
		return 1;
}

function whenIsUserInterrogato($id_sessione, $id_alunno)
{
	$classe=getUserClass();
	if($id_alunno==null)
		$id_alunno=getUserId();

	$query="SELECT DATE_FORMAT(data, '%d/%m/%Y') AS d
    	FROM interrogazioni_interrogati AS i
    	JOIN interrogazioni_giorni AS g
    	ON g.id=i.id_giorno
    	JOIN interrogazioni_sessioni AS s
    	ON g.id_sessione=s.id
        WHERE  g.id_sessione='$id_sessione'
        AND s.classe='$classe'
        AND i.id_alunno='$id_alunno'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	return $row["d"];
}

function getNumPrenotati($id_sessione)
{
	$classe=getUserClass();
	$query="SELECT COUNT(*) AS c
FROM interrogazioni_alunni AS a
JOIN interrogazioni_interrogati AS i
ON i.id_alunno=a.id
JOIN interrogazioni_giorni AS g
ON g.id=i.id_giorno
JOIN interrogazioni_sessioni AS s
ON s.id=g.id_sessione
WHERE s.id='$id_sessione'
AND s.classe='$classe'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	return $row["c"];
}

function getNumInterrogati($id_sessione)
{
	$classe=getUserClass();
	$query="SELECT COUNT(*) AS c
FROM interrogazioni_alunni AS a
JOIN interrogazioni_interrogati AS i
ON i.id_alunno=a.id
JOIN interrogazioni_giorni AS g
ON g.id=i.id_giorno
JOIN interrogazioni_sessioni AS s
ON s.id=g.id_sessione
WHERE s.id='$id_sessione'
AND s.classe='$classe'
AND g.data<=CURRENT_DATE";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	return $row["c"];
}

function getStatoSessione($id_sessione)
{
	$classe=getUserClass();
	$query="SELECT stato
					FROM interrogazioni_sessioni
					WHERE classe='$classe'
					AND id=$id_sessione";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	if($row["stato"]==1 || getNumInterrogati($id_sessione)==getNumAlunniClasse())
		return "Conclusa";
	else if(getNumPrenotati($id_sessione)==getNumAlunniClasse())
		return "Pianificata";
	else
		return "In corso";

}

function getNumAlunniClasse()
{
	$classe=getUserClass();
	$query="SELECT COUNT(*) AS c
					FROM interrogazioni_alunni
					WHERE classe='$classe'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();

	return $row["c"];
}

function getClassBySessione($id_sessione)
{
	$query="SELECT classe AS c
					FROM interrogazioni_sessioni
					WHERE id='$id_sessione'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["c"];
}

function getSessioneByGiorno($id_giorno)
{
	$query="SELECT s.id AS id
					FROM interrogazioni_sessioni AS s
					JOIN interrogazioni_giorni AS g 
					ON g.id_sessione=s.id
					WHERE g.id='$id_giorno'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["id"];
}

function isUserAdmin()
{
	$utente=$_SESSION['username'];
	$query="SELECT auth_admin
    	FROM interrogazioni_alunni
        WHERE email='$utente'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	return $row["auth_admin"];
}

function anyUnreadMessages()
{
	$classe=getUserClass();
	$query="SELECT id, nome, cognome
					FROM `interrogazioni_alunni`
					WHERE classe='$classe'
					ORDER BY cognome ASC, nome ASC";
	$risultato=database()->query($query);
	while($row=$risultato->fetch_assoc())
	{
		if(unreadMessages($row["id"])==true)
			$daleggere=true;
	}
	return $daleggere;
}

function unreadMessages($id_alunno)
{
	if(isUserAdmin())
	{
		$query="SELECT letto
					FROM `interrogazioni_messaggi`
					WHERE id_mittente='$id_alunno'
					AND letto=0
					AND id_destinatario IS NULL ";
		$risultato=database()->query($query);
		if($risultato->num_rows>0)
			return true;
	}
}

function userLastSeen($id_alunno)
{
	$query="SELECT MAX(data_inserimento) AS lastseen
					FROM `interrogazioni_log`
					WHERE id_utente='$id_alunno'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
		return $row["lastseen"];
}

function isUserOnline($id_alunno)
{
	$now=new DateTime();
	$lastseen=new DateTime(userLastSeen($id_alunno));
	$diff=$lastseen->diff($now);
	if($diff->format('%a')==0 && $diff->format('%h')==0 && $diff->format('%i')<2)
	{
		return true;
	}
	else
		return false;
}