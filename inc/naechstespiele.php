<h1>nächste Spiele</h1>
<?php
	require(__DIR__."/../lib/teamnamen.php");
	//require(__DIR__."/../lib/zeitverzoegerung.php");
	$db_bloecke = $pdo -> prepare("SELECT blockID,bezeichnung,status FROM block WHERE status<2 AND sichtbar_anzeige=1");
    $db_bloecke -> execute();
	while ($block = $db_bloecke -> fetch(PDO::FETCH_ASSOC)) {
		echo "<div class='block";
		if($block["status"]==0) echo " notset";
		echo "'>";
		echo "<h2>".$block["bezeichnung"]."</h2><table id='naechstespiele'>";
		$db_spiele = $pdo -> prepare("SELECT teamID1,teamID2,fixiert,zeit FROM spiel WHERE status=0 AND blockID=".$block["blockID"]." ORDER BY position,spielID");
		$db_spiele -> execute();
		while ($spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
			echo "<tr";
			if($spiel["fixiert"]==1) echo " class='fixed'";
            echo "><td><b>".$teams[$spiel["teamID1"]]."</b>";
			if(isset($spiel["teamID2"])) echo " vs. <b>".$teams[$spiel["teamID2"]]."</b>";
			else echo " allein";
			echo "</td></tr><tr class='time";
			if($spiel["fixiert"]) echo " fixed";
			echo "'><td>".substr($spiel["zeit"],0,5)."</td></tr>";
		}
		echo "</table></div>";
	}
?>
