<?php
	function verzoegerung($startzeit,$dauer) {
		$db_verzoegerungen = $pdo -> prepare("SELECT * FROM verzoegerung ORDER BY zeit");
		$db_verzoegerungen -> execute();
		$stat_verz = 0;
		$zeit = strftime("%H:%M",mktime(
				substr($startzeit,0,2),
				substr($startzeit,3,2)+$dauer));
		while($verzoegerung = $db_verzoegerungen -> fetch(PDO::FETCH_ASSOC)) {
			if($verzoegerung["zeit"]<=$zeit) $stat_verz += $verzoegerung["verschiebung"];
			$zeit = strftime("%H:%M",mktime(
				substr($startzeit,0,2),
				substr($startzeit,3,2)+$dauer+$stat_verz));
		}
		return $zeit;
	}
?>