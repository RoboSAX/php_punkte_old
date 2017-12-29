<?php
	$dauer = 180; //in Sekunden
	$xml = simplexml_load_file("../settings.xml");
    $dauer = $xml->spieldauer;

	require_once(__DIR__."/db_connection.php");

	$db_spiele = $pdo -> prepare("SELECT zeit FROM spiel_aktiv");
	$db_spiele -> execute();
	$db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
	if($db_spiel) {
		$zeit = mktime(substr($db_spiel["zeit"],0,2),substr($db_spiel["zeit"],3,2),substr($db_spiel["zeit"],6,2));
		if($zeit>time()) {
			echo ($zeit-time())."s";
		}
		else {
			echo ($zeit-time()+$dauer)."s";
		}
		echo "<input type=\"submit\" name=\"spielbeenden\" value=\"Spiel beeenden\">";
	}
?>