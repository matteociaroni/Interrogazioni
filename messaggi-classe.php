<?php
include_once "util.php";
if(!isUserAdmin())
{
	header("Location: index.php");
}
?>
<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Interrogazioni </title>
	<?php
	echo file_get_contents('navbar/head.html');
	?>
</head>

<body>
<?php
echo file_get_contents('navbar/navbar-bootstrap.html');
?>
<div class="container">
	<br>
	<h1> Messaggi </h1>
	<br>
	<h2> Classe <? echo getUserClass(); ?> </h2>
	<br>
	<table id="table" class="table table-bordered">
		<thead>
		<tr>
			<th scope="col">Alunno</th>
			<th scope="col">Dettagli</th>
		</tr>
		</thead>
		<tbody>
		<?
		$classe=getUserClass();
		$query="SELECT id, nome, cognome
					FROM `interrogazioni_alunni`
					WHERE classe='$classe'
					ORDER BY cognome ASC, nome ASC";
		$risultato=database()->query($query);
		while($row=$risultato->fetch_assoc())
		{
			echo '<tr class="table';
			if(unreadMessages($row["id"])==true) echo "-primary";
			echo '">';
			echo '<td>'.$row["cognome"].' '.$row["nome"].'</td>';
			echo '<td><a href="conversazione.php?id='.$row["id"].'">Dettagli</a></td></tr>';
		}
		?>
		</tbody>
	</table>
	<br>
</div>
</body>
</html>
