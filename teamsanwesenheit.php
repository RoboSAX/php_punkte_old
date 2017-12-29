<?php
	require(__DIR__."/lib/db_connection.php");
	$db_connection = new DB_Connection();
	$pdo = $db_connection -> getPDO();
	/*
	$found = false;
	for($i=0;$i<20;$i++) if(isset($_POST["nicht anwesend"])) {
		$found = true;
		break;
	}*/	
	if(isset($_POST["anwesend"])) {
		$db_teams = $pdo -> prepare('UPDATE team SET anwesenheit=1 WHERE teamID=:teamID');
		$db_teams -> execute(array(":teamID"=>$_POST["anwesend"]));
	}
	else if(isset($_POST["nichtanwesend"])) {
		$db_teams = $pdo -> prepare('UPDATE team SET anwesenheit=0 WHERE teamID=:teamID');
		$db_teams -> execute(array(":teamID"=>$_POST["nichtanwesend"]));
	}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="lib/stylesheet.css">
        <style>
            tbody tr td:first-child {
				text-align:right;
			}
			
        </style>
    </head>
    <body>
		<h1>Teamliste</h1>
		<form method="post">
			<table class="list">
				<thead>
					<tr>
						<th></th>
						<th>Teamname</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db_teams = $pdo -> prepare('SELECT * FROM team');
						$db_teams -> execute();
						while ($team=$db_teams->fetch(PDO::FETCH_ASSOC)) {
							echo "<tr><td>";
							if(!$team["anwesenheit"]) echo "<button name='anwesend' value='".$team["teamID"]."'><b>nicht anwesend</b></button>";
							else echo "<button name='nichtanwesend' value='".$team["teamID"]."'>anwesend</button>";
							echo "</td><td>".$team["teamname"]."</td></tr>";
						}
					?>
				</tbody>
			</table>
		</form>
    </body>
</html>
