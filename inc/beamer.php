<?php
	if(isset($_POST["inhaltdazu"]) and isset($_POST["files"])) {
		foreach($_POST["files"] as $file) {
			$db_add = $pdo -> prepare("INSERT HIGH_PRIORITY INTO anzeige (pfad,dauer) VALUES (:pfad,5000)");
			$db_add -> execute(array(":pfad"=>$file));
		}
	}
	else if(isset($_POST["inhaltentf"]) and isset($_POST["contents"])) {
		$db_delete = $pdo -> prepare("DELETE FROM anzeige WHERE anzeigeID IN (:querypart)");
		$db_delete -> execute(array(":querypart"=>implode(",",$_POST["contents"])));
		//echo implode(",",$_POST["contents"]);
	}
	else if(isset($_POST["speichern"])) {
		$i = 0;
		while(isset($_POST[$i])) {
			$db_change = $pdo -> prepare("UPDATE anzeige SET dauer=:dauer,aktiv=:aktiv WHERE anzeigeID=:anzeigeID");
			$anzeigeID = $_POST[$i];
			$db_change -> execute(array(":dauer"=>$_POST["dauer".$anzeigeID],":aktiv"=>(isset($_POST["aktiv".$anzeigeID]) ? 1 : 0),":anzeigeID"=>$anzeigeID));
			$i++;
		}
	}
?>
<h1>Beamer steuern</h1>
<div class="anleitung">
	<center>Anleitung</center>
	Auf dieser Webseite lassen sich die angezeigten Inhalte auf den Beamern verändern.
	<br>
	In der linken Auswahlliste befinden sich alle Dateien, die angezeigt werden könnten. Sie sind nach Ordnern gruppiert.
	<br>
	In der rechten Auswahlliste befinden sich die derzeitigen Einträge in der Datenbank.
	<br>
	Beide Auswahllisten sind vertikal durch Ziehen am unteren rechten Ende vergrößerbar.
	<br>
	Eine Mehrfachauswahl ist durch Halten der Strg-Taste, der Shift-Taste oder der linken Maustaste während des Darüberfahrens möglich.
	<br>
	Der Knopf mit der Aufschrift "&gt;" erzeugt einen neuen Eintrag in der Datenbank, der den Pfad der links ausgewählten Datei hat.
	<br>
	Der Knopf mit der Aufschrift "X" löscht einen Eintrag aus der Datenbank, woraufhin die rechte Liste aktualisiert wird. Leider kann bisher nur ein Element gleichzeitig gelöscht werden.
	<br>
	Das Bedienfeld bestehend aus einer Tabelle ermöglicht eine Modifikation der Anzeigedauer und dem Aktivsein.
	<br>
	Durch Einstellen des Aktivseins ist eine einfache vorübergehende Zuschaltung bzw. Deaktivierung von Webseiten möglich.
	<br>
	Nach Veränderungen im Bedienfeld muss der Knopf mit der Aufschrift "Speichern" getätigt werden, da die Veränderungen sonst nicht übernommen und verworfen werden.
</div>
<br>
<form method="post" id="beamersteuern">
	<select multiple name="files[]" size="10">
		<?php
			foreach(glob("*",GLOB_ONLYDIR) as $dir) {
				echo "<optgroup label=\"".$dir."\">";
				foreach(glob($dir."/*") as $file) {
					echo "<option>".$file."</option>";
				}
				echo "</optgroup>";
			}
		?>
	</select>
	<select multiple name="contents[]" size="10">
		<?php
			$db_anzeige = $pdo -> prepare('SELECT anzeigeID,pfad FROM anzeige WHERE anzeigeID!=0');
			$db_anzeige -> execute();
			while($anzeige = $db_anzeige -> fetch(PDO::FETCH_ASSOC)) {
				echo "<option value=\"".$anzeige["anzeigeID"]."\">".$anzeige["pfad"]."</option>";
			}
		?>
	</select>
	<div id="controls">
		<input type="submit" name="inhaltdazu" value=">" title="Einfügen">
		<input type="submit" name="inhaltentf" value="X" title="Löschen">
	</div>
	<br>
	<br>
	<table class="list">
		<thead>
			<tr>
				<th>Pfad</th>
				<th>Dauer (in ms)</th>
				<th>Aktiv</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$db_anzeige = $pdo -> prepare('SELECT anzeigeID,pfad,dauer, aktiv FROM anzeige WHERE anzeigeID!=0 ORDER BY anzeigeID');
				$db_anzeige -> execute();
				$i = 0;
				while($row = $db_anzeige -> fetch(PDO::FETCH_ASSOC)) {
					echo "<tr><td>".$row["pfad"]."</td><td><input type='number' min='500' step='500' onchange='this.addClass(\"changed\")' name='dauer".$row["anzeigeID"]."' value='".$row["dauer"]."'></td><td><input type='checkbox' name='aktiv".$row["anzeigeID"]."'".($row["aktiv"]==1 ? " checked" : "")."></td><input type='hidden' name='".$i."' value='".$row["anzeigeID"]."'></tr>";
					$i++;
				}
			?>
		</tbody>
	</table>
	<input type="submit" name="speichern" value="Speichern">
	<script>
		//$("input").onchange = function(){this.addClass("changed");}
	</script>
</form>