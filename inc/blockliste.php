<h1>Blockliste</h1>
<form method="post" action="?abschnitt=block">
    <table class="list">
        <thead>
            <tr>
                <th></th>
                <th>BlockID</th>
                <th>Bezeichnung</th>
                <th>Art</th>
                <th>Zeit</th>
                <th>Status</th>
                <th>manuelle<br>Generierung</th>
				<th>in Anzeige<br>sichtbar</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $status = array("ausstehend","läuft","abgeschlossen","unbekannter Status");
                $art = array("allein","gegeneinander","KO","unbekannte Art");
                $db_block = $pdo->prepare("SELECT blockID,art,status,manuell,sichtbar_anzeige,ablauf.bezeichnung,ablauf.zeit FROM block JOIN ablauf ON block.ablaufID=ablauf.ablaufID");
                $db_block -> execute();
                $i = 0;
                while ($block = $db_block->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td><input type=\"radio\" name=\"block\" value=\"".$block["blockID"]."\"".($i == 0 ? " checked" : "")."></td>";
                    echo "<td>".$block["blockID"]."</td>";
                    echo "<td>".$block["bezeichnung"]."</td>";
                    echo "<td>".(isset($art[$block["art"]]) ? $art[$block["art"]] : $art[3])."</td>";
                    echo "<td>".substr($block["zeit"],0,5)."</td>";
                    echo "<td>".(isset($status[$block["status"]]) ? $status[$block["status"]] : $status[3])."</td>";
                    echo "<td>".($block["manuell"] ? "ja" : "nein")."</td>";
					echo "<td>".($block["sichtbar_anzeige"] ? "ja" : "nein")."</td></tr>";
                    $i++;
                }
            ?>
        </tbody>
    </table>
    <!--<input type="submit" name="addblock" value="Block hinzufügen">-->
    <input type="submit" name="changeblock" value="Block ändern">
	<input type="submit" name="startblock" value="Starten und andere Blöcke beenden">
</form>
