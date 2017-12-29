<?php
	/*
	header("Refresh:1");
	$db_spiele = $pdo -> prepare("SELECT zeit FROM spiel_aktiv WHERE spielID=:spielID");
	$db_spiele -> execute(array(":spielID"=>$_GET["spiel"]));
	$spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
	$zeit = mktime(substr($spiel["zeit"],0,2),substr($spiel["zeit"],3,2),substr($spiel["zeit"],6,2));
	if($zeit-time()>0) {
		echo ($zeit-time())."s";
	}
	else {
		echo ($zeit-time()+dauer)."s";
	}
	if($zeit-time()+dauer<=0) {
		echo "fertig";
		header("location: ?state=3&spiel=".$_GET["spiel"]);
	}
	*/
?>

<?php
	const dauer = 8; //in Sekunden
	
	require_once(__DIR__."/lib/db_connection.php");

	include_once(__DIR__."/lib/teamnamen.php");
	
	if(isset($_GET["state"]) and ($_GET["state"]<4)) { // and !(isset($_GET["spiel"]) and ($_GET["state"]!=1))
		$state = $_GET["state"];
	}
	else {
		header("location: ?state=0");
	}

	//wenn möglich, offenes Spiel aufrufen
	$db_spiel_aktiv = $pdo -> prepare("SELECT spielID FROM spiel_aktiv");
    $db_spiel_aktiv -> execute();
	$spiel_aktiv = $db_spiel_aktiv -> fetch(PDO::FETCH_ASSOC);
	if(!empty($spiel_aktiv["spielID"]) and $state<2) header("location: ?state=2&spiel=".$spiel_aktiv["spielID"]);

	if(isset($_POST["starten"])) {
		$db_add = $pdo -> prepare("INSERT HIGH_PRIORITY INTO spiel_aktiv (spielID,zeit) VALUES (:spielID,:zeit)");
        $db_add -> execute(array(":spielID"=>$_GET["spiel"],":zeit"=>strftime("%H:%M:%S",time()+$_POST["countdown"])));
		header("location: ?state=2&spiel=".$_GET["spiel"]);
	}
	else if(isset($_POST["spielbeenden"])) {
		$db_delete = $pdo -> prepare("TRUNCATE spiel_aktiv");
        $db_delete -> execute();
		header("location: ?state=3&spiel=".$_GET["spiel"]);
	}
	else if(isset($_POST["speichern"])) {
		$db_change = $pdo -> prepare("UPDATE spiel SET punkte1=:punkte1,punkte2=:punkte2,handeingriffe1=:handeingriffe1,handeingriffe2=:handeingriffe2,strafpunkte1=:strafpunkte1,strafpunkte2=:strafpunkte2,status=2 WHERE spielID=:spielID");
		$db_change -> execute(array(":spielID"=>$_GET["spiel"],":punkte1"=>$_POST["punkte1"],":punkte2"=>$_POST["punkte2"],":handeingriffe1"=>$_POST["handeingriffe1"],":handeingriffe2"=>$_POST["handeingriffe2"],":strafpunkte1"=>$_POST["strafpunkte1"],":strafpunkte2"=>$_POST["strafpunkte2"]));
		/* Team rauswerfen
		$db_spiele = $pdo -> prepare("SELECT blockID,teamID1,teamID2 FROM spiel WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_GET["spiel"]));
		$spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
		$db_bloecke = $pdo -> prepare("SELECT ko FROM block WHERE blockID=:blockID");
		$db_bloecke -> execute(array(":blockID"=>$spiel["blockID"]));
		$block = $db_bloecke -> fetch(PDO::FETCH_ASSOC);
		if(($block["ko"]) and (max($_POST["punkte1"]-$_POST["strafpunkte1"]-$_POST["handeingriffe1"],0)<max($_POST["punkte2"]-$_POST["strafpunkte2"]-$_POST["handeingriffe2"],0))) {
			$db_kickteam = $pdo -> prepare("UPDATE team SET ko=:blockID WHERE teamID=:teamID");
			$db_kickteam -> execute(array(":teamID"=>$spiel["teamID1"],":blockID"=>$spiel["blockID"]));
		}
		else {
			$db_kickteam = $pdo -> prepare("UPDATE team SET ko=:blockID WHERE teamID=:teamID");
			$db_kickteam -> execute(array(":teamID"=>$spiel["teamID2"],":blockID"=>$spiel["blockID"]));
		}
		*/
		header("location: ?state=0");
	}
?>
<html>
    <head>
        <meta charset="UTF-8">
		<script src="lib/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="lib/stylesheet.css">
    </head>
    <body class="no-overflow-horizontal">
		<?php if($state==0): ?><!--Liste noch nicht vollendeter Spiele-->
			<h1>Schiedsrichter - Spielübersicht</h1>
			<div class="anleitung">
				<center>Anleitung</center>
				Es werden alle nicht vollendeten Spiele aus laufenden Spielblöcken in Tabellenform angezeigt.
				<br>
				Durch das Klicken auf den Link "vorbereiten" ist eine genauere Ansicht und der Start des Spiels sowie das Eintragen der Ergebnisse möglich.
			</div>
			<br>
			<form method="post">
				<table class="list">
					<thead>
						<tr>
							<th>BlockID</th>
							<th>Team 1</th>
							<th>Team 2</th>
							<th>Uhrzeit</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$db_bloecke = $pdo -> prepare('SELECT blockID FROM block WHERE status=1');
							$db_bloecke -> execute();
							$bloecke = $db_bloecke -> fetchAll();
							$db_spiele = $pdo -> prepare('SELECT spielID,teamID1,teamID2,blockID,zeit FROM spiel WHERE status=0 ORDER BY blockID,position,spielID');
							$db_spiele -> execute();
							while ($spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
								$found = false;
								foreach($bloecke as $block) $found = $found || ($block["blockID"]==$spiel["blockID"]);
								if($found) continue;
								echo "<tr><td>".$spiel["blockID"]."</td><td>".$teams[$spiel["teamID1"]]."</td><td>".(isset($spiel["teamID2"]) ? $teams[$spiel["teamID2"]] : "-")."</td><td>".$spiel["zeit"]."</td><td><a href=\"?state=1&spiel=".$spiel["spielID"]."\">vorbereiten</a></td></tr>";
							}
						?>
					</tbody>
				</table>
			</form>
		<?php elseif($state==1): ?><!--Spiel ansehen, Countdown und Timer starten-->
			<a href="?state=0">zurück zur Spielübersicht</a>
			<h1>Schiedsrichter - Spieldetails</h1>
			<?php
				if(!isset($_GET["spiel"])) {
					include_once(__DIR__."/lib/message.php");
					drop_message(3,false,0,"Es wurde kein Spiel ausgewählt. Bitte verändern Sie nicht die Querys.");
				}
			?>
			<div class="anleitung">
				<center>Anleitung</center>
				Es werden die Eigenschaften des ausgewählten Spiels angezeigt.
			</div>
			<br>
			<form method="post">
				<table class="list">
					<tbody>
						<?php
							$db_spiele = $pdo -> prepare("SELECT teamID1,teamID2,zeit FROM spiel WHERE spielID=:spielID");
							$db_spiele -> execute(array(":spielID"=>$_GET["spiel"]));
							$spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
						?>
						<tr>
							<td>Team 1:</td>
							<td><?=$teams[$spiel["teamID1"]]?></td>
						</tr>
						<tr>
							<td>Team 2:</td>
							<td><?=$teams[$spiel["teamID2"]]?></td>
						</tr>
						<tr>
							<td>Uhrzeit:</td>
							<td><?=$spiel["zeit"]?></td>
						</tr>
						<tr>
							<td>Countdown:</td>
							<td><input type="number" name="countdown" min="0" value="3">s</td>
						</tr>
					</tbody>
				</table>
				<input type="submit" name="starten" value="Spiel starten">
			</form>
		<?php elseif($state==2): ?><!--Countdown und Timer-->
			<script>
				setInterval(function() {
					$.ajax({
                		method: "POST",
                		url: "lib/countdown.php"
            		}).done(function(result) {
                		$("#time").html(result);
            		}).error(function(result) {
						alert("not great");
					});
				},1000);
        	</script>
			<h1>Schiedsrichter - Spiel läuft</h1>
			<h2>
				<?php
					$db_spiele = $pdo -> prepare("SELECT teamID1,teamID2,zeit FROM spiel WHERE spielID=:spielID");
					$db_spiele -> execute(array(":spielID"=>$_GET["spiel"]));
					$spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
					if(isset($spiel["teamID2"])) echo $teams[$spiel["teamID1"]]." vs. ".$teams[$spiel["teamID2"]];
					else echo $teams[$spiel["teamID1"]]." allein";
				?>
			</h2>
			<form method="post" id="time"></form>
		<?php else: ?><!--Ergebnis eintragen-->
			<h1>Schiedsrichter - Ergebnis</h1>
			<div class="anleitung">
				<center>Anleitung</center>
				Auf dieser Seite können die Ergebnisse des Spiels eingetragen werden.
				<br>
				Zum Bestätigen der eingegebenen Informationen klicken Sie bitte auf den mit "Speichern" beschrifteten Knopf.
			</div>
			<br>
			<form method="post">
				<table>
					<thead>
						<tr>
							<th></th>
							<?php
								$db_spiele = $pdo -> prepare("SELECT teamID1,teamID2 FROM spiel WHERE spielID=:spielID");
								$db_spiele -> execute(array(":spielID"=>$_GET["spiel"]));
								$db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
							?>
							<th><?=$teams[$db_spiel["teamID1"]]?></th>
							<th><?=(isset($db_spiel["teamID2"]) ? $teams[$db_spiel["teamID2"]] : "")?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Punkte:</td>
							<td><input type="number" name="punkte1"></td>
							<td><input type="number" name="punkte2"></td>
						</tr>
						<tr>
							<td>Handeingriffe:</td>
							<td><input type="number" name="handeingriffe1"></td>
							<td><input type="number" name="handeingriffe2"></td>
						</tr>
						<tr>
							<td>Strafpunkte:</td>
							<td><input type="number" name="strafpunkte1"></td>
							<td><input type="number" name="strafpunkte2"></td>
						</tr>
					</tbody>
				</table>
				<input type="submit" name="speichern" value="Speichern">
			</form>
		<?php endif; ?>
    </body>
</html>