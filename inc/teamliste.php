<h1>Teamliste</h1>
<form method="post" action="?abschnitt=team">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>TeamID</th>
				<th>Teamname</th>
				<th>Anwesenheit</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$db_teams = $pdo -> prepare('SELECT * FROM team');
				$db_teams -> execute();
				while ($team = $db_teams -> fetch(PDO::FETCH_ASSOC)) {
					echo "<tr><td><input type='radio' name='team' value='".$team["teamID"]."'></td>";
					echo "<td>".$team["teamID"]."</td>";
					echo "<td>".$team["teamname"]."</td>";
					echo "<td>".$team["anwesenheit"]."</td></tr>";
				}
			?>
		</tbody>
	</table>
	<input type="submit" name="addteam" value="Team erstellen">
	<input type="submit" name="changeteam" value="Team ändern">
</form>