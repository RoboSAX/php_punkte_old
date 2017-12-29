<?php
    $ablaufID = "";
    $position = "";
    $typ = 1;
    $bezeichnung = "";
    $zeit = "";
    $dauer = 0;
    $puffer = 0;
	if(isset($_POST["add"])) {
        $db_add = $pdo -> prepare("INSERT INTO ablauf (position,typ,bezeichnung,dauer,puffer) VALUES (:position,:typ,:bezeichnung,:dauer,:puffer)");
        $db_add -> execute(array(":position"=>$_POST["position"],":typ"=>$_POST["typ"],":bezeichnung"=>$_POST["bezeichnung"],":dauer"=>$_POST["dauer"],":puffer"=>$_POST["puffer"]));
        if($_POST["typ"]==1) {
            $db_ablauf = $pdo -> prepare("SELECT ablaufID FROM ablauf WHERE (position=:position) and (typ=:typ) and (bezeichnung=:bezeichnung)");
            $db_ablauf -> execute(array(":position"=>$_POST["position"],":typ"=>$_POST["typ"],":bezeichnung"=>$_POST["bezeichnung"]));
            $db_event = $db_ablauf -> fetch(PDO::FETCH_ASSOC);
            $db_add = $pdo -> prepare("INSERT INTO block (ablaufID) VALUES (:ablaufID)");
            $db_add -> execute(array(":ablaufID"=>$db_event["ablaufID"]));
        }
        include(__DIR__."/../spielgenerieren.php");
        header("Location: ?abschnitt=uebersicht");
    }
    else if(isset($_POST["change"])) {
        $db_change = $pdo -> prepare("UPDATE ablauf SET position=:position,typ=:typ,bezeichnung=:bezeichnung,dauer=:dauer,puffer=:puffer WHERE ablaufID=:ablaufID");
        $db_change -> execute(array(":ablaufID"=>$_POST["ablaufID"],":position"=>$_POST["position"],":typ"=>$_POST["typ"],":bezeichnung"=>$_POST["bezeichnung"],":dauer"=>$_POST["dauer"],":puffer"=>$_POST["puffer"]));
		include(__DIR__."/../spielgenerieren.php");
        header("Location: ?abschnitt=uebersicht");
    }
	else if(isset($_POST["changeevent"])) {
        $db_ablauf = $pdo -> prepare("SELECT ablaufID,position,typ,bezeichnung,zeit,dauer,puffer FROM ablauf WHERE ablaufID=:ablaufID");
        $db_ablauf -> execute(array(":ablaufID"=>$_POST["ablaufID"]));
        $db_event = $db_ablauf -> fetch(PDO::FETCH_ASSOC);
        $ablaufID = $db_event["ablaufID"];
        $position = $db_event["position"];
        $typ = $db_event["typ"];
        $bezeichnung = $db_event["bezeichnung"];
        $zeit = $db_event["zeit"];
        $dauer = $db_event["dauer"];
        $puffer = $db_event["puffer"];
    }
?>
<?php if(!isset($_POST["changeevent"])): ?>
	<h1>Zeitabschnitt erstellen</h1>
<?php else: ?>
	<h1>Zeitabschnitt bearbeiten</h1>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="ablaufID" value="<?=$ablaufID?>">
    <table>
        <tbody>
			<tr>
                <td>Position:</td>
                <td><input type="number" name="position" value="<?=$position?>"></td>
            </tr>
            <tr>
                <td>Typ:</td>
                <td>
					<select name="typ">
                        <option value="0" <?php echo (($typ==0) ? " selected" : "");?>>Ansprache</option>
                        <option value="1" <?php echo (($typ==1) ? " selected" : "");?>>Block</option>
                        <option value="2" <?php echo (($typ==2) ? " selected" : "");?>>Mittag</option>
						<option value="3" <?php echo (($typ==3) ? " selected" : "");?>>Teamleiterbesprechung</option>
						<option value="4" <?php echo (($typ==4) ? " selected" : "");?>>Verzögerung</option>
						<option value="5" <?php echo (($typ==5) ? " selected" : "");?>>Siegerehrung</option>
                    </select>
				</td>
            </tr>
            <tr>
                <td>Bezeichnung:</td>
                <td><input name="bezeichnung" value="<?=$bezeichnung?>"></td>
			</tr>
			<tr>
                <td>Uhrzeit:</td>
                <td><input type="time" name="dauer" value="<?=$zeit?>" disabled></td>
            </tr>
            <tr>
                <td>Dauer:<br>(in Minuten)</td>
                <td><input type="number" name="dauer" value="<?=$dauer?>"> Nur benutzen, wenn kein Block unter "Typ" ausgewählt ist.</td>
            </tr>
			<tr>
                <td>Puffer:<br>(in Minuten)</td>
                <td><input type="number" name="puffer" value="<?=$puffer?>"></td>
            </tr>
        </tbody>
	</table>
	<?php if(!isset($_POST["changeevent"])): ?>
		<input type="submit" name="add" value="Zeitabschnitt erstellen">
	<?php else: ?>
		<input type="submit" name="change" value="Zeitabschnitt bearbeiten">
	<?php endif; ?>  
</form>