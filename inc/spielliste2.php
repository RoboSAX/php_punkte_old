<h1>Spielliste</h1>
<form method="post" action="?abschnitt=spiel">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>SpielID</th>
				<th>Block</th>
				<th>Position</th>
				<th>Teams</th>
				<th>Punkte</th>
				<th>Handeingriffe</th>
				<th>Strafpunkte</th>
				<th>Status</th>
				<th>fixiert</th>
				<th>Uhrzeit</th>
			</tr>
		</thead>
		<tbody>
			<?php
				require(__DIR__."/../lib/teamnamen.php");
				$db_spiele = $pdo -> prepare('SELECT * FROM spiel');
				$db_spiele -> execute();
				while ($db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC)) {
					echo "<tr><td><input type='checkbox' name='spiel[]' value='".$db_spiel["spielID"]."'></td>";
					echo "<td>".$db_spiel["spielID"]."</td>";
					echo "<td>".$db_spiel["blockID"]."</td>";
					echo "<td>".$db_spiel["position"]."</td>";
					echo "<td>".$teams[$db_spiel["teamID1"]].(isset($db_spiel["teamID2"]) ? "<br>".$teams[$db_spiel["teamID2"]] : "")."</td>";
					echo "<td>".$db_spiel["punkte1"]."<br>".$db_spiel["punkte2"]."</td>";
					echo "<td>".$db_spiel["handeingriffe1"]."<br>".$db_spiel["handeingriffe2"]."</td>";
					echo "<td>".$db_spiel["strafpunkte1"]."<br>".$db_spiel["strafpunkte2"]."</td>";
					echo "<td>".$db_spiel["status"]."</td>";
					echo "<td>".$db_spiel["fixiert"]."</td>";
					echo "<td>".substr($db_spiel["zeit"],0,5)."</td></tr>";
				}
			?>
		</tbody>
	</table>
	<input type="submit" name="changespiel" value="Spiel ändern">
	<input type="submit" name="switchspiel" value="Spiele tauschen">
	<input type="submit" name="startspiel" value="Spiel starten">
</form>