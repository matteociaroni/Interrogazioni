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
	<style>
		.col-lg-4
		{
			margin-bottom: 25px;
		}
	</style>
</head>

<body>
<?php
echo file_get_contents('navbar/navbar-bootstrap.html');
?>
<div class="container">
	<br>
	<h1> Notifiche </h1>
	<br>
	<?
	$id_alunno=getUserId();
	$query="SELECT id_notifica, data, titolo, messaggio, letta, link, id_mittente, nome, cognome
					FROM `interrogazioni_notifiche`
					LEFT JOIN interrogazioni_alunni
					ON id_mittente=id
					WHERE id_alunno='$id_alunno'
					ORDER BY id_notifica DESC";
	$risultato=database()->query($query);
	$i=0;
	echo '<div class="row">';
	while($row=$risultato->fetch_assoc())
	{
		$i++;
		?>
		<div class="col-lg-4">
			<div class="container" style="max-width: 500px">
				<div class="card <? if($row["letta"]==0) echo "text-white bg-info"; ?>">
					<div class="card-header"><? echo date("d/m/Y H:i", strtotime($row["data"]));
					if(isset($row["cognome"]))
						echo '&emsp;da '.$row["cognome"].' '.substr($row["nome"], 0, 1).'.'; ?></div>
					<div class="card-body">
						<h5 class="card-title"><? echo $row["titolo"]; ?></h5>
						<p class="card-text"><? echo $row["messaggio"]; ?></p>
						<? if(filter_var($row["link"], FILTER_VALIDATE_URL)!=false) echo '<a href="'.$row["link"].'" class="btn btn-standard">Dettagli</a>'?>
					</div>
				</div>
			</div>
			<br>
		</div>
		<?
		if($i%3==0)
		{
			echo '</div><div class="row">';
		}
	}
	$query="UPDATE interrogazioni_notifiche
					SET letta=1
					WHERE id_alunno='$id_alunno'";
	$risultato=database()->query($query);
	?>
</div>
<br>
<?
if(isUserAdmin())
{
	?>
	<div class="divbottom">
		<div class="col">
			<a href="nuova-notifica.php">
				<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
					<i class="material-icons">send</i>
				</button>
			</a>
		</div>
	</div>
	<!--	<script>
			document.getElementById("navbarDropdown").style.display = "block";
			var x = '<a class="dropdown-item" href="nuova-sessione.php"> Nuova sessione </a>';
			document.getElementById("mng").innerHTML += x;
		</script>-->
	<?
}
?>
</div>
</body>
</html>
