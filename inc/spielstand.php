<?php
	require_once(__DIR__."/../lib/db_connection.php");
	
	//Blöcke
	$db_bloeckex = $pdo -> prepare("SELECT ablaufID,bezeichnung FROM ablauf WHERE sichtbar_spielstand=1 ORDER BY blockID");
	$db_bloeckex -> execute();
	$bloecke = array();
	while($db_blockx = $db_bloeckex -> fetch(PDO::FETCH_ASSOC)) {
		$bloecke[$db_blockx["blockID"]] = $db_blockx["bezeichnung"];
	}
	$blockcount = sizeof($bloecke);
	
	//Daten
	//$punkte = array();
	//$punkte = array_fill(0,$blockcount,array());
	/*
	for($i = 0; $i < $blockcount; $i++) {
		$punkte[$i] = array();
	}
	
	$strafpunkte = array();
	for($i = 0; $i < $blockcount; $i++) {
		$strafpunkte[$i] = array();
	}
	
	$db_spiele = $pdo -> prepare("SELECT blockID,teamID1,teamID2,punkte1,punkte2,strafpunkte1,strafpunkte2,handeingriffe1,handeingriffe2 FROM spiel");
	$db_spiele -> execute();
	while($db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
		
		echo "blockID: ".$db_spiel["blockID"]."; teamID1: ".$db_spiel["teamID1"]."; teamID2: ".$db_spiel["teamID2"]."<br>";
		$sef = (int)$db_spiel["teamID1"];
		$fef = (int)$db_spiel["blockID"];
		$kuv = 5; 
		$punkte[$fef][$kuv] = 5;
		
		$punkte[$db_spiel["blockID"]][$db_spiel["teamID1"]] = $db_spiel["punkte1"];
		$punkte[$db_spiel["blockID"]][$db_spiel["teamID2"]] = $db_spiel["punkte2"];
		$strafpunkte[$db_spiel["blockID"]][$db_spiel["teamID1"]] = $db_spiel["strafpunkte1"]+$db_spiel["handeingriffe1"];
		$strafpunkte[$db_spiel["blockID"]][$db_spiel["teamID2"]] = $db_spiel["strafpunkte2"]+$db_spiel["handeingriffe2"];
		
	}
	//$imax = sizeof($punkte);
	//$jmax = sizeof($punkte[0]);
	
	print_r($punkte);*/
?>
<h1>Spielstand</h1>
<table>
	<thead>
		<tr>
			<th>Platz</th>
			<th>Teamname</th>
			<?php
				for($i = 0; $i < $blockcount; $i++) {
					echo "<th colspan='2'>".$bloecke[$i]."</th>";
				}
			?>
		</tr>
	</thead>
	<tbody></tbody></table>
		<?php
			/*
			$k = 1;
			for($i = 0; $i < 5; $i++) {
				for($j = 1; $j < 5; $j++) {
					echo "<tr><td rowspan='2'>".$k."</td><td rowspan='2'>".$teams[$j]."</td><td rowspan='2'>".($punkte[$i][$j]-$strafpunkte[$i][$j])."</td><td>".$punkte[$i][$j]."</td></tr><tr><td>".$strafpunkte[$i][$j]."</td></tr>";
				}
				$k++;
			}
			*/
			/*
			$k = 1;
			$db_teams = $pdo -> prepare("SELECT teamID,teamname,ko FROM team");
			$db_teams -> execute();
			while($db_team = $db_teams -> fetch(PDO::FETCH_ASSOC)) {
				echo "<tr><td>".$k."</td><td>".$db_team["teamname"]."</td>";
				$db_bloecke = $pdo -> prepare("SELECT blockID,bezeichnung FROM block WHERE sichtbar_spielstand=1 ORDER BY blockID");
				$db_bloecke -> execute();
				while($db_block = $db_bloecke -> fetch(PDO::FETCH_ASSOC)) {
					$db_spiele = $pdo -> prepare("SELECT teamID1,punkte1,punkte2,strafpunkte1,strafpunkte2,handeingriffe1,handeingriffe2,status FROM spiel WHERE blockID=:blockID AND (teamID1=:teamID OR teamID2=:teamID) ORDER BY blockID");
					$db_spiele -> execute(array(":teamID"=>$db_team["teamID"],":blockID"=>$db_block["blockID"]));
					$db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
					if(!isset($db_spiel) or ($db_spiel["status"]<2) or ($db_team["ko"]>$db_block["blockID"])) {
						echo "<td colspan='2'>".$db_block["bezeichnung"]."</td>";
						continue;
					}
					if($db_team["teamID"]==$db_spiel["teamID1"]) {
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
				$k++;
			}*/
			/*
			require("../lib/ergebnisseberechnen.php");
			$ergebnisse = new ergebnisse;
			$tabeller = $ergebnisse->ausgeben($pdo,true);*/




				


			require("/../lib/ergebnisseberechnen.php");
			echo json_encode($tabelle);
		?>
	<!--</tbody>-->
<!--</table>-->