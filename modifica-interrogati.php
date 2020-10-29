<?php
include_once "util.php";
$id_giorno=$_GET["giorno"];
if(!isUserAdmin() || !isset($id_giorno))
{
	header("Location: index.php");
}
else
{
	$classe=getUserClass();
	$query="SELECT cognome, nome, DATE_FORMAT(data, '%d/%m/%Y'), i.id AS idi, s.id AS ids
			FROM interrogazioni_giorni AS g
 			JOIN interrogazioni_sessioni AS s
 			ON s.id=g.id_sessione
 			JOIN interrogazioni_interrogati AS i
 			ON g.id=i.id_giorno
 			JOIN interrogazioni_alunni AS a
 			ON a.id=i.id_alunno
 			WHERE s.classe='$classe' AND g.id='$id_giorno'
 			ORDER BY cognome, nome";
	$risultato=database()->query($query);
	$id_sessione=getSessioneByGiorno($id_giorno);
}
?>
	<! Autore: Matteo Ciaroni>
	<html>
	<head>
		<title> Interrogazioni </title>
		<?php
		echo file_get_contents('navbar/head.html');
		?>
		<style>
			.loader,
			.loader:before,
			.loader:after
			{
				background: #335fac;
				-webkit-animation: load1 1s infinite ease-in-out;
				animation: load1 1s infinite ease-in-out;
				width: 1em;
				height: 4em;
			}

			.loader
			{
				color: #335fac;
				text-indent: -9999em;
				margin: 88px auto;
				position: relative;
				font-size: 11px;
				-webkit-transform: translateZ(0);
				-ms-transform: translateZ(0);
				transform: translateZ(0);
				-webkit-animation-delay: -0.16s;
				animation-delay: -0.16s;
			}

			.loader:before,
			.loader:after
			{
				position: absolute;
				top: 0;
				content: '';
			}

			.loader:before
			{
				left: -1.5em;
				-webkit-animation-delay: -0.32s;
				animation-delay: -0.32s;
			}

			.loader:after
			{
				left: 1.5em;
			}

			@-webkit-keyframes load1
			{
				0%,
				80%,
				100%
				{
					box-shadow: 0 0;
					height: 4em;
				}
				40%
				{
					box-shadow: 0 -2em;
					height: 5em;
				}
			}

			@keyframes load1
			{
				0%,
				80%,
				100%
				{
					box-shadow: 0 0;
					height: 4em;
				}
				40%
				{
					box-shadow: 0 -2em;
					height: 5em;
				}
			}

		</style>
		<script>
			function getNumAlunni()
			{
				let x = document.getElementById("select").options.length;
				if(!x > 0)
					disableSelect();
			}

			function disableSelect()
			{
				document.getElementById("select").setAttribute("disabled", "disabled");
				document.getElementById("select").innerHTML += "<option>Nessuno rimasto</option>"
			}
		</script>
	</head>

	<body onload="getNumAlunni();">
	<?php
	echo file_get_contents('navbar/navbar-bootstrap.html');
	?>
	<div class="container">
		<br>
		<h1>Modifica Interrogati</h1>
		<br>
		<p>Ad ogni modifica viene inviata una notifica all'alunno coinvolto.</p>
		<br>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th>Cognome</th>
				<th>Nome</th>
				<th>Elimina</th>
			</tr>
			</thead>
			<tbody>
			<?
			while($row=$risultato->fetch_assoc())
			{
				echo '<tr><td>'.$row["cognome"].'</td><td>'.$row["nome"].'</td><td>';
				echo '<a href="elimina-interrogato.php?id='.$row["idi"].'&giorno='.$id_giorno.'">Elimina</a><br>';
				echo '</td></tr>';
			}
			?>
			</tbody>
		</table>
		<br>
		<hr>
		<form method="post">
			<div class="container" style="max-width: 600px">
				<div class="form-row">
					<div class="col">
						<div class="form-group">
							<select class="form-control" name="nuovo" id="select">
								<?
								$query="SELECT * FROM interrogazioni_alunni WHERE classe='$classe' ORDER BY cognome ASC, nome ASC";
								$risultato=database()->query($query);
								$i=0;
								while($row=$risultato->fetch_assoc())
								{
									if(whenIsUserInterrogato($id_sessione, $row["id"])==null)
									{
										$interrogabili[$i]=$row["id"];
										$i++;
										echo '<option value="'.$row["id"].'">'.$row["cognome"].' '.$row["nome"].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col">
						<br>
						<input class="btn btn-raised btn-standard" type="submit" name="formSubmit"
							   value="Aggiungi"/><br>
					</div>
				</div>
				<hr>
				<br>
				<br>
				<?
				if($i>0)
					echo '<input class="btn btn-raised btn-warning btn-lg" type="submit" name="formSubmit" value="Random"/><br>';
				else
					echo '<input class="btn btn-raised btn-warning btn-lg" type="submit" name="formSubmit" value="Random" disabled/><br>';
				?>
				<div style="display: none" id="loader" class="loader">Loading...</div>
			</div>
		</form>
	</div>
	</body>
	</html>
<?php
if($_POST['formSubmit']=="Aggiungi")
{
	$alunno=$_POST["nuovo"];
	$email=$_POST["email"];
	if(isUserAdmin() && isset($alunno))
	{
		include_once "../util.php";
		$sql="INSERT INTO interrogazioni_interrogati (id_alunno, id_giorno) VALUES ('$alunno', '$id_giorno')";
		database()->query($sql);
		$link="http://acquamarinapesaro.altervista.org/Vari/interrogazioni/dettagli-giorno.php?id=".$id_giorno;
		$id_mittente=getUserId();
		$sql="INSERT INTO interrogazioni_notifiche (id_notifica, id_alunno, titolo, messaggio, data, link, id_mittente) VALUES (NULL, '$alunno', 'Interrogato', 'Sei stato aggiunto all interogazione', null, '$link', '$id_mittente')";
		database()->query($sql);

		/*			//Invio email
					$classe=getUserClass();
					$sql="SELECT email FROM interrogazioni_alunni WHERE id=$alunno AND classe='$classe'";
					$risultato=database()->query($sql);
					$row=$risultato->fetch_assoc();
					$email=$row["email"];
					mail($email, "Interrogazioni ".$classe, "Sei stato estratto! http://acquamarinapesaro.altervista.org/Vari/interrogazioni/dettagli-giorno.php?id=".$id_giorno);
		*/

		echo "<script type='text/javascript'> document.location = 'modifica-interrogati.php?giorno=$id_giorno'; </script>";
	}
}

if($_POST['formSubmit']=="Random")
{
	if(isUserAdmin())
	{
		$scelto=rand(0, $i-1);
		$query="SELECT * FROM interrogazioni_alunni WHERE classe='$classe' AND id='$interrogabili[$scelto]'";
		$risultato=database()->query($query);
		$row=$risultato->fetch_assoc();
		echo '<br><h1 id="scelto" style="display:none;">'.$row["cognome"].' '.$row["nome"].'</h1>';
		echo '<script>
				document.getElementById("loader").style.display="block";
				setTimeout(function()
				{
					document.getElementById("loader").style.display="none";
					document.getElementById("scelto").style.display="block";
				}, 3000);
</script>';
	}
}
?>