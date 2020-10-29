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
	<h1> Le mie Interrogazioni </h1>
	<br>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th scope="col">Data</th>
			<th scope="col">Materia</th>
			<th scope="col">Descrizione</th>
			<th scope="col">Dettagli</th>
		</tr>
		</thead>
		<tbody>
		<?
		$classe=getUserClass();
		$id_alunno=getUserId();
		$oggi=date('Y-m-d');
		$query="SELECT materia, descrizione, gio.id AS id, data
					FROM `interrogazioni_sessioni` AS sess
					JOIN interrogazioni_giorni AS gio ON sess.id=gio.id_sessione
					JOIN interrogazioni_interrogati AS i ON i.id_giorno=gio.id
					WHERE sess.classe='$classe'
					AND id_alunno=$id_alunno
					ORDER BY data ASC";

		$risultato=database()->query($query);
		$lastDate=null;
		while($row=$risultato->fetch_assoc())
		{
			if($row["data"]==$oggi)
				echo '<tr class="table-primary"><td scope="row">'.date("d/m/Y", strtotime($row["data"])).'</td>';
			else
				echo '<tr><td scope="row">'.date("d/m/Y", strtotime($row["data"])).'</td>';
			echo '<td>'.$row["materia"].'</td>
      <td>'.$row["descrizione"].'</td>
      <td><a href="dettagli-giorno.php?id='.$row["id"].'">Dettagli</a></td>
    </tr>';
		}

		?>
		</tbody>
	</table>
	<br>
</div>
</body>
</html>
