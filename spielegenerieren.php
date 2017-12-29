<?php
	const teamquery = "SELECT teamID FROM team WHERE anwesenheit=1 ORDER BY teamID";
	$verstrichenebloecke = array();
	
	//Startzeit und Spieldauer auslesen
	if(file_exists("settings.xml")) { //Datei ist vorhanden
		$xml = simplexml_load_file("settings.xml");
		$zeit = strtotime(date("m/d/Y")." ".$xml->startzeit.":00");
		$spieldauer = $xml->spieldauer+$xml->spielpause;
		$bewertungslimit = (int)$xml->bewertungslimit;
		}
	else { //Datei ist nicht vorhanden, Standardwerte werden gesetzt
		$zeit = strtotime(date("m/d/Y")." 10:00:00");
		$spieldauer = 300;
		$bewertungslimit = null;
	}
	
	//Datenbankzugang herstellen
	require_once(__DIR__."/lib/db_connection.php");

	//Durchlaufen aller Events
	$db_ablauf = $pdo -> prepare("SELECT ablaufID,typ,dauer,puffer FROM ablauf ORDER BY position,ablaufID");
	$db_ablauf -> execute();
	while($event = $db_ablauf -> fetch(PDO::FETCH_ASSOC)) {

		//Fallunterscheidung Block - anderes Event
		if($event["typ"]==1) { //Block

			//den dem Event entsprechenden Block selektieren
			$db_bloecke = $pdo -> prepare("SELECT blockID,art,zeit,status FROM block WHERE ablaufID=".$event["ablaufID"]);
			$db_bloecke -> execute();
			$block = $db_bloecke -> fetch(PDO::FETCH_ASSOC);

			//vergangene Blöcke sollen nicht angetastet werden
			if($block["status"]=2) {
				//beim vergangenen Zyklus generierte Spiele entfernen
				$db_spiel = $pdo -> prepare("DELETE FROM spiel WHERE status=0 AND blockID=".$block["blockID"]);
				$db_spiel -> execute();

				//freie Teams in diesem Block herausfinden 					UNBEENDET -> Integration von ergebnisseberechnen.php
				$db_teams = $pdo -> prepare("SELECT teamID FROM team WHERE anwesenheit=1 AND ko NOT IN (".explode(",",$verstrichenebloecke).") MINUS SELECT teamID FROM team JOIN spiel ON team.teamID=spiel.teamID1 OR team.teamID=spiel.teamID2 WHERE spiel.blockID=".$block["blockID"]." GROUP BY teamID");
				$db_teams -> execute();
				$teams = $db_teams -> fetchAll();
				$teamcount = count($teams);
				$maxruns = ($block["art"]==0) ? $teamcount : ceil($teamcount/2);
				
				for($i = 0; $i<$maxruns; $i++) {
					$teamID1 = $db_teams -> fetch(PDO::FETCH_ASSOC)["teamID"];
					if($ko) $teamID2 = null;
					else {
						if((($i+1)==$maxruns) and ($teamcount % 2 == 1)) $teamID2 = null;
						else $teamID2 = $db_teams -> fetch(PDO::FETCH_ASSOC)["teamID"];
					}
					
					//Spiel in Datenbank eintragen
					$db_spieldazu = $pdo -> prepare("INSERT INTO spiel (blockID,position,teamID1,teamID2,zeit) VALUES (:blockID,:position,:teamID1,:teamID2,:zeit)");
					$db_spieldazu -> execute(array(":blockID"=>$block["blockID"],":position"=>$i,":teamID1"=>$teamID1,":teamID2"=>$teamID2,":zeit"=>$zeit));

					//Zeit wird für den nächsten Schleifendurchlauf erhöht
					$zeit += $spieldauer*1000;
				}
			}
			$zeit += $event["puffer"]*60*1000;

			//Zeit in Ablaufeintrag schreiben
			$db_updateablauf = $pdo -> prepare("UPDATE ablauf SET zeit=$zeit,dauer=".($maxruns*$spieldauer)." WHERE ablaufID=".$event['ablaufID']);
			$db_updateablauf -> execute();

			array_push($verstrichenebloecke,$block["blockID"]);
		}
		else { //anderes Event
			$zeit += ($event["dauer"]+$event["puffer"])*60*1000;
			
			//Zeit in Ablaufeintrag schreiben
			$db_updateablauf = $pdo -> prepare("UPDATE ablauf SET zeit=$zeit WHERE ablaufID=".$event['ablaufID']);
			$db_updateablauf -> execute();
		}
	}
?>