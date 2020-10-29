<?php
include_once "util.php";
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
	<h1> Classe <? echo getUserClass(); ?> </h1>
	<br>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Cognome</th>
			<th scope="col">Nome</th>
		</tr>
		</thead>
		<tbody>
		<?
		$classe=getUserClass();
		$query="SELECT id, nome, cognome
					FROM interrogazioni_alunni
					WHERE classe='$classe'
					ORDER BY cognome ASC , nome ASC";
		$i=1;
		$risultato=database()->query($query);

		while($row=$risultato->fetch_assoc())
		{
			echo '<tr ';
			if(isUserOnline($row["id"]))
				echo 'class="table-success"';
			echo '>
      <td>'.$i++.'</td>
      <td>'.$row["cognome"].'</td>
      <td>'.$row["nome"].'</td>';
		}

		?>
		</tbody>
	</table>
	<br>
</div>
<?
if(isUserAdmin())
{
	?>
	<div class="divbottom">
		<div class="col">
			<a href="nuovo-alunno.php">
				<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
					<i class="material-icons">add</i>
				</button>
			</a>
		</div>
	</div>
	<?
}
?>
</body>
</html>
