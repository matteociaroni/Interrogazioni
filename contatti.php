<! Autore: Matteo Ciaroni>
<?php
include_once "util.php";
?>
<html>
<head>
	<title> Interrogazioni </title>
	<?php
	echo file_get_contents('navbar/head.html');
	?>
	<style>
		.chat-list
		{
			padding: 0;
			font-size: 15px;
		}

		.chat-list li
		{
			margin-bottom: 10px;
			overflow: auto;
			color: #ffffff;
		}

		.chat-list .chat-img
		{
			float: left;
			width: 50px;
		}

		.chat-list .chat-img img
		{
			-webkit-border-radius: 50px;
			-moz-border-radius: 50px;
			border-radius: 50px;
			width: 100%;
		}

		.chat-list .chat-message
		{
			-webkit-border-radius: 50px;
			-moz-border-radius: 50px;
			border-radius: 50px;
			background: #5a99ee;
			display: inline-block;
			padding: 13px 25px;
			position: relative;
		}

		.chat-list .chat-message:before
		{
			content: "";
			position: absolute;
			top: 15px;
			width: 0;
			height: 0;
		}

		.chat-list .chat-message h5
		{
			margin: 0 0 5px 0;
			font-weight: 600;
			line-height: 100%;
			font-size: .9rem;
		}

		.chat-list .chat-message p
		{
			line-height: 18px;
			margin: 0;
			padding: 0;
		}

		.chat-list .chat-body
		{
			margin-left: 20px;
			float: left;
			width: 70%;
		}

		.chat-list .in .chat-message:before
		{
			left: -12px;
			border-bottom: 20px solid transparent;
			border-right: 20px solid #5a99ee;
		}

		.chat-list .out .chat-img
		{
			float: right;
		}

		.chat-list .out .chat-body
		{
			float: right;
			margin-right: 20px;
			text-align: right;
		}

		.chat-list .out .chat-message
		{
			background: #fc6d4c;
		}

		.chat-list .out .chat-message:before
		{
			right: -12px;
			border-bottom: 20px solid transparent;
			border-left: 20px solid #fc6d4c;
		}

		.card .card-header:first-child
		{
			-webkit-border-radius: 0.2rem 0.2rem 0 0;
			-moz-border-radius: 0.2rem 0.2rem 0 0;
			border-radius: 0.2rem 0.2rem 0 0;
		}

		.card .card-header
		{
			background: #17202b;
			border: 0;
			font-size: 1rem;
			padding: .65rem 1rem;
			position: relative;
			font-weight: 600;
			color: #ffffff;
		}

		.data
		{
			font-size: 11px;
		}
	</style>
</head>

<body onload="document.getElementById('send').scrollIntoView();">
<?
echo file_get_contents('navbar/navbar-bootstrap.html');
?>
<div class="container">
	<br>
	<h1> Contatti </h1>
	<br>
	<h3> Chatta con gli amministratori della tua classe </h3>
	<br>
	<div style="text-align: left">
		<div class="card">
			<div class="card-body height3">
				<ul class="chat-list">
					<?
					$id_alunno=getUserId();
					$query="SELECT cognome, nome, id_mittente, id_destinatario, messaggio, data_inserimento, letto
					FROM `interrogazioni_messaggi`
					JOIN interrogazioni_alunni AS a ON id_mittente=a.id
					WHERE id_destinatario='$id_alunno' OR (id_mittente='$id_alunno' AND id_destinatario IS NULL)
					ORDER BY data_inserimento ASC";
					$risultato=database()->query($query);
					while($row=$risultato->fetch_assoc())
					{
						?>
						<li class="<? if(isset($row["id_destinatario"])) echo "in"; else echo "out"; ?>">
							<div class="chat-body">
								<div class="chat-message">
									<? if(isset($row["id_destinatario"])) echo '<h5>'.$row["cognome"].' '.$row["nome"].'</h5>'; ?>
									<p><? echo $row["messaggio"] ?></p>
									<small class="data"><? echo date("d/m H:i", strtotime($row["data_inserimento"]));?></small>
								</div>
							</div>
						</li>
						<?
					}
					$query="UPDATE interrogazioni_messaggi SET letto='$id_alunno'
					WHERE id_destinatario='$id_alunno'";
					database()->query($query);
					?>
				</ul>
				<form method="post">
					<div class="form-group">
						<label class="bmd-label-floating">Scrivi</label>
						<textarea class="form-control" name="messaggio" rows="2"></textarea>
					</div>
			<br>
			<input class="btn btn-raised btn-standard" id="send" type="submit" name="formSubmit"
				   value="Invia"><br/>

			</form>
		</div>
	</div>
</div>

<?
if(isUserAdmin())
{
	?>
	<div class="divbottom">
		<div class="col">
			<a href="messaggi-classe.php">
				<button type="button" class="btn
				<?
				if(anyUnreadMessages()==true)
					echo " btn-warning";
				else
					echo " btn-standard";
?>
 bmd-btn-fab">
					<i class="material-icons">message</i>
				</button>
			</a>
		</div>
	</div>
	<?
}
?>
</div>
<?php
if($_POST['formSubmit']=="Invia")
{
	$messaggio=$_POST['messaggio'];
	$id_alunno=getUserId();
	if(!strlen(trim($messaggio)))
	{
		echo '<p style="color:red">Inserisci il messaggio</p>';
	}
	else
	{
		include_once "util.php";
		$query="INSERT INTO interrogazioni_messaggi (id, id_mittente, messaggio) VALUES (NULL, $id_alunno, '$messaggio')";
		database()->query($query);
		echo '<script> document.location=document.location; </script>';
	}
}
?>
<br>
</div>
</body>
</html>