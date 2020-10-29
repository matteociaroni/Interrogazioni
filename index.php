<?php
include_once "util.php";
?>
<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Interrogazioni</title>
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
	<h1> <? echo getUserName().' '.getUserSurname(); ?> </h1>
	<br>
	<h2> Prossime Interrogazioni </h2>
	<br>
	<table id="table" class="table table-bordered">
		<thead>
		<tr>
			<th scope="col">Data</th>
			<th scope="col">Materia</th>
			<th scope="col">Dettagli</th>
		</tr>
		</thead>
		<tbody>
		<?
		$classe=getUserClass();
		$oggi=date('d/m/Y');
		$domani=new DateTime('tomorrow');
		$domani=$domani->format('d/m');
		$query="SELECT materia, id_sessione, gio.id AS id, DATE_FORMAT(data, '%d/%m/%Y') AS data
					FROM `interrogazioni_sessioni` AS sess
					JOIN interrogazioni_giorni AS gio ON sess.id=gio.id_sessione
					WHERE sess.classe='$classe'
					AND gio.data>=CURRENT_DATE
					AND gio.data<CURRENT_DATE + INTERVAL 7 DAY
					ORDER BY data";

		$risultato=database()->query($query);
		$lastDate=null;
		while($row=$risultato->fetch_assoc())
		{
			if($row["data"]==$oggi)
			{
				echo '<tr class="table-primary"><td scope="row">'.$row["data"].'</td>';
			}
			else
			{
				echo '<tr><td scope="row">'.$row["data"].'</td>';
			}
			echo '<td>'.$row["materia"].'</td>';
			if(isUserInterrogato($row["id"]))
			{
				echo '<td class="you">';
			}
			else
			{
				echo '<td>';
			}
			echo '<a href="dettagli-giorno.php?id='.$row["id"].'">Dettagli</a></td></tr>';
		}

		?>
		</tbody>
	</table>
	<?
	if(isUserAdmin() && anyUnreadMessages()==true)
	{
		echo '<div class="alert alert-danger" role="alert">
  Ci sono nuovi messaggi: <a href="messaggi-classe.php">Controlla</a></div>';
	}
	?>
	<br>
	<div class="divbottom">
		<div class="col">
			<a href="notifiche.php">
				<button type="button" class="btn <?
				$id_alunno=getUserId();
				$query="SELECT id_notifica, data, titolo, messaggio, letta
					FROM `interrogazioni_notifiche`
					WHERE id_alunno='$id_alunno'
					AND letta=0
					ORDER BY id_notifica DESC";
				$risultato=database()->query($query);
				if($risultato->num_rows>0)
					echo ' btn-warning ';
				else
					echo ' btn-standard ';
				?>
bmd-btn-fab">
					<i class="material-icons">notifications</i>
				</button>
			</a>
		</div>
	</div>
</div>
</body>
</html>
