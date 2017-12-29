<?php
	$db_teams = $pdo -> prepare("SELECT teamID,teamname FROM team");
	$db_teams -> execute();
	$teams = array();
	while($db_team = $db_teams -> fetch(PDO::FETCH_ASSOC)) {
		$teams[$db_team["teamID"]] = $db_team["teamname"];
	}
?>