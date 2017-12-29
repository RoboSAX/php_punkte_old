<?php
	require_once(__DIR__."/../lib/db_connection.php");
	
	if(file_exists("../settings.xml")) { //Datei ist vorhanden
		$xml = simplexml_load_file("../settings.xml");
		$strafe = (int)$xml->strafe;
		$handeingriff = (int)$xml->handeingriff;
		$bewertungslimit = (int)$xml->bewertungslimit;
	}
	else { //Datei ist nicht vorhanden, Standardwerte werden gesetzt
		$strafe = 5;
		$handeingriff = 3;
		$bewertungslimit = 3;
	}

	$db_bloecke = $pdo -> prepare("SELECT blockID,art FROM block WHERE status>0 ORDER BY blockID");
	$db_bloecke -> execute();
	while($block = $db_bloecke -> fetch(PDO::FETCH_ASSOC)) {
		$db_spiele = $pdo -> prepare("SELECT spielID,teamID1,teamID2,punkte1,punkte2,strafpunkte1,strafpunkte2,handeingriffe1,handeingriffe2 FROM spiel WHERE status=2 ORDER BY position,spielID");
		$db_spiele -> execute();
		while($spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->spielID = $spiel["spielID"];
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->punkte = max($spiel["punkte1"]-$strafe*$spiel["strafpunkte1"]-$handeingriff*$spiel["handeingriffe1"],0);
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->pluspunkte = $spiel["punkte1"];
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->minuspunkte = $strafe*$spiel["strafpunkte1"]+$handeingriff*$spiel["handeingriffe1"];
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->strafpunkte = $spiel["strafpunkte1"];
			$tabelle[$block["blockID"]][$spiel["teamID1"]]->handeingriffe = $spiel["handeingriffe1"];
			$punktzahlen[$spiel["teamID1"]][$block["blockID"]] = $tabelle[$block["blockID"]][$spiel["teamID1"]]->punkte;
			if($block["art"]>0) {	
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->spielID = $spiel["spielID"];
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->punkte = max($spiel["punkte2"]-$strafe*$spiel["strafpunkte2"]-$handeingriff*$spiel["handeingriffe2"],0);
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->pluspunkte = $spiel["punkte2"];
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->minuspunkte = $strafe*$spiel["strafpunkte2"]+$handeingriff*$spiel["handeingriffe2"];
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->strafpunkte = $strafe*$spiel["strafpunkte2"];
				$tabelle[$block["blockID"]][$spiel["teamID2"]]->handeingriffe = $handeingriff*$spiel["handeingriffe2"];
				$punktzahlen[$spiel["teamID2"]][$block["blockID"]] = $tabelle[$block["blockID"]][$spiel["teamID2"]]->punkte;
			}
		}
		//TODO: Spielbewertungssystem -> einfliessende Spiele markieren lassen (via Eigenschaft) & ko nach Block x neu setzen
		foreach($tabelle[$block["blockID"]] as $team) {
			for($i = 0; $i<20; $i++) {

				if(!isset($tabelle[$i][$team])) continue;
				//sjhdbfuhjs
			}
		}
	}

			/*
			$db_spiele = $pdo -> prepare("SELECT spiel.blockID,teamID1,teamID2,punkte1,punkte2,strafpunkte1,strafpunkte2,handeingriffe1,handeingriffe2,block.art FROM spiel LEFT JOIN block ON spiel.blockID=block.blockID WHERE spiel.status=2 AND block.status=2 ORDER BY spiel.blockID,position,spielID");
			$db_spiele -> execute();
			while($spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
				$this->$tabelle[$spiel["blockID"]][$spiel["teamID1"]]->punkte = max($spiel["punkte1"]-$this->$strafe*$spiel["strafpunkte1"]-$this->$handeingriff*$spiel["handeingriffe1"],0);
				$this->$tabelle[$spiel["blockID"]][$spiel["teamID1"]]->pluspunkte = $spiel["punkte1"];
				$this->$tabelle[$spiel["blockID"]][$spiel["teamID1"]]->minuspunkte = $this->$strafe*$spiel["strafpunkte1"]+$this->$handeingriff*$spiel["handeingriffe1"];
				$this->$tabelle[$spiel["blockID"]][$spiel["teamID1"]]->strafpunkte = $spiel["strafpunkte1"];
				$this->$tabelle[$spiel["blockID"]][$spiel["teamID1"]]->handeingriffe = $spiel["handeingriffe1"];
				if($spiel["art"]>0) {
					$this->$tabelle[$spiel["blockID"]][$spiel["teamID2"]]->punkte = max($spiel["punkte2"]-$this->$strafe*$spiel["strafpunkte2"]-$this->$handeingriff*$spiel["handeingriffe2"],0);
					$this->$tabelle[$spiel["blockID"]][$spiel["teamID2"]]->pluspunkte = $spiel["punkte2"];
					$this->$tabelle[$spiel["blockID"]][$spiel["teamID2"]]->minuspunkte = $this->$strafe*$spiel["strafpunkte2"]+$this->$handeingriff*$spiel["handeingriffe2"];
					$this->$tabelle[$spiel["blockID"]][$spiel["teamID2"]]->strafpunkte = $spiel["strafpunkte2"];
					$this->$tabelle[$spiel["blockID"]][$spiel["teamID2"]]->handeingriffe = $spiel["handeingriffe2"];
				}


			$k = 1;
			$db_teams = $pdo -> prepare("SELECT teamID,teamname,ko FROM team");
			$db_teams -> execute();
			while($team = $db_teams -> fetch(PDO::FETCH_ASSOC)) {
				echo "<tr><td>".$k."</td><td>".$team["teamname"]."</td>";
				$db_bloecke = $pdo -> prepare("SELECT blockID,bezeichnung FROM block WHERE sichtbar_spielstand=1 ORDER BY blockID");
				$db_bloecke -> execute();
				while($block = $db_bloecke -> fetch(PDO::FETCH_ASSOC)) {
					$db_spiele = $pdo -> prepare("SELECT teamID1,punkte1,punkte2,strafpunkte1,strafpunkte2,handeingriffe1,handeingriffe2,status FROM spiel WHERE blockID=:blockID AND (teamID1=:teamID OR teamID2=:teamID) ORDER BY blockID");
					$db_spiele -> execute(array(":teamID"=>$team["teamID"],":blockID"=>$db_block["blockID"]));
					$spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
					if(!isset($spiel) or ($spiel["status"]<2) or ($team["ko"]>$block["blockID"])) {
						echo "<td colspan='2'>".$block["bezeichnung"]."</td>";
						continue;
					}
					if($team["teamID"]==$spiel["teamID1"]) {
						$punkte = $db_spiel["punkte1"];
						$strafpunkte = $db_spiel["strafpunkte1"]+$db_spiel["handeingriffe1"];
					}
					else {
						$punkte = $db_spiel["punkte2"];
						$strafpunkte = $db_spiel["strafpunkte2"]+$db_spiel["handeingriffe2"];
					}
					echo "<td>".max($punkte-$strafpunkte,0)."</td><td>".$punkte."-".$strafpunkte."</td>";
				}
				echo "</tr>";
				$k++;*/
?>