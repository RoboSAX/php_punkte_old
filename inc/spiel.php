<?php
	$blockID = 0;
	$position = 0;
	$teamID1 = 0;
	$teamID2 = null;
	$punkte1 = 0;
	$punkte2 = 0;
	$strafpunkte1 = 0;
	$strafpunkte2 = 0;
	$handeingriffe1 = 0;
	$handeingriffe2 = 0;
	$status = 0;
	$fixiert = 0;
	$zeit = "";
    $punktegeaendert = 0;
    $zeitgeaendert = 0;
	
	if(isset($_POST["add"])) {
		$db_add = $pdo -> prepare("INSERT INTO spiel (blockID,position,teamID1,teamID2,punkte1,punkte2,handeingriffe1,handeingriffe2,strafpunkte1,strafpunkte2,status,fixiert,zeit,punktegeaendert,zeitgeaendert) VALUES (:spielID,:blockID,:position,:teamID1,:teamID2,:punkte1,:punkte2,:handeingriffe1,:handeingriffe2,:strafpunkte1,:strafpunkte2,:status,:fixiert,:zeit,:punktegeaendert,:zeitgeaendert)");
		$db_add -> execute(array(":blockID"=>$_POST["blockID"],":position"=>$_POST["position"],":teamID1"=>$_POST["teamID1"],":teamID2"=>($_POST["teamID2"]>=0 ? $_POST["teamID2"] : null),":punkte1"=>$_POST["punkte1"],":punkte2"=>$_POST["punkte2"],":handeingriffe1"=>$_POST["handeingriffe1"],":handeingriffe2"=>$_POST["handeingriffe2"],":strafpunkte1"=>$_POST["strafpunkte1"],":strafpunkte2"=>$_POST["strafpunkte2"],":status"=>$_POST["status"],":fixiert"=>(isset($_POST["fixiert"]) ? 1 : 0),":zeit"=>$_POST["zeit"],":punktegeaendert"=>(isset($_POST["punktegeaendert"]) ? 1 : 0),":zeitgeaendert"=>(isset($_POST["zeitgeaendert"]) ? 1 : 0)));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
	}
	else if(isset($_POST["change"])) {
        $db_change = $pdo -> prepare("UPDATE spiel SET blockID=:blockID,position=:position,teamID1=:teamID1,teamID2=:teamID2,punkte1=:punkte1,punkte2=:punkte2,handeingriffe1=:handeingriffe1,handeingriffe2=:handeingriffe2,strafpunkte1=:strafpunkte1,strafpunkte2=:strafpunkte2,status=:status,fixiert=:fixiert,zeit=:zeit,punktegeaendert=:punktegeaendert,zeitgeaendert=:zeitgeaendert WHERE spielID=:spielID");
        $db_change -> execute(array(":spielID"=>$_POST["spielID"],":blockID"=>$_POST["blockID"],":position"=>$_POST["position"],":teamID1"=>$_POST["teamID1"],":teamID2"=>($_POST["teamID2"]>=0 ? $_POST["teamID2"] : null),":punkte1"=>$_POST["punkte1"],":punkte2"=>$_POST["punkte2"],":handeingriffe1"=>$_POST["handeingriffe1"],":handeingriffe2"=>$_POST["handeingriffe2"],":strafpunkte1"=>$_POST["strafpunkte1"],":strafpunkte2"=>$_POST["strafpunkte2"],":status"=>$_POST["status"],":fixiert"=>(isset($_POST["fixiert"]) ? 1 : 0),":zeit"=>$_POST["zeit"],":punktegeaendert"=>(isset($_POST["punktegeaendert"]) ? 1 : 0),":zeitgeaendert"=>(isset($_POST["zeitgeaendert"]) ? 1 : 0)));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
    }
	else if(isset($_POST["changespiel"])) {
		$db_spiele = $pdo -> prepare("SELECT * FROM spiel WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][0]));
		$db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
		
		$blockID = $db_spiel["blockID"];
		$position = $db_spiel["position"];
		$teamID1 = $db_spiel["teamID1"];
		$teamID2 = $db_spiel["teamID2"];
		$punkte1 = $db_spiel["punkte1"];
		$punkte2 = $db_spiel["punkte2"];
		$strafpunkte1 = $db_spiel["strafpunkte1"];
		$strafpunkte2 = $db_spiel["strafpunkte2"];
		$handeingriffe1 = $db_spiel["handeingriffe1"];
		$handeingriffe2 = $db_spiel["handeingriffe2"];
		$status = $db_spiel["status"];
		$fixiert = $db_spiel["fixiert"];
		$zeit = $db_spiel["zeit"];
        $punktegeaendert = $db_spiel["punktegeaendert"];
        $zeitgeaendert = $db_spiel["zeitgeaendert"];
	}
	else if(isset($_POST["switchspiel"])) {
        $db_spiele = $pdo -> prepare("SELECT position FROM spiel WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][0]));
        $db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
        $positionspiel[0] = $db_spiel["position"];
        $db_spiele = $pdo -> prepare("SELECT position FROM spiel WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][1]));
        $db_spiel = $db_spiele -> fetch(PDO::FETCH_ASSOC);
        $positionspiel[1] = $db_spiel["position"];

		$db_spiele = $pdo -> prepare("UPDATE spiel SET position=:position,zeitgeaendert=1,fixiert=1 WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][0],":position"=>$positionspiel[1]));
        $db_spiele -> execute(array(":spielID"=>$_POST["spiel"][1],":position"=>$positionspiel[0]));
		
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
	}
    else if(isset($_POST["startspiel"])) {
        $db_spiele = $pdo -> prepare("UPDATE spiel SET status=1 WHERE spielID=:spielID");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][0]));
        $db_delete = $pdo -> prepare("TRUNCATE spiel_aktiv");
        $db_delete -> execute();
        $db_spiele = $pdo -> prepare("INSERT INTO spiel_aktiv (spielID,zeit) VALUES (:spielID,:zeit)");
		$db_spiele -> execute(array(":spielID"=>$_POST["spiel"][0],":zeit"=>strftime("%H:%M:%S",time())));
        header("Location: schiedsrichter.php");
    }
?>
<?php if(!isset($_POST["changespiel"])): ?>
	<h1>Spiel erstellen</h1>
<?php else: ?>
	<h1>Spiel bearbeiten</h1>
<?php endif; ?>
<form method="post">
	<input type="hidden" name="spielID" value="<?=$_POST["spiel"][0]?>">
	<table>
		<tbody>
            <tr>
                <td>Block:</td>
                <td>
                    <select name="blockID">
                        <?php
							$db_bloecke = $pdo -> prepare('SELECT blockID,bezeichnung FROM block');
							$db_bloecke -> execute();
							while ($db_block = $db_bloecke -> fetch(PDO::FETCH_ASSOC)) {
								echo "<option value='".$db_block["blockID"]."'".($db_block["blockID"]==$blockID ? " selected" : "").">".$db_block["bezeichnung"]."</option>";
							}
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Position:</td>
                <td><input type="number" name="position" value="<?=$position?>"></td>
            </tr>
            <tr>
                <td>Team 1:</td>
                <td>
                    <select name="teamID1">
                        <?php
                             $db_teams = $pdo -> prepare("SELECT teamID,teamname FROM team");
                             $db_teams -> execute();
                             while ($row=$db_teams -> fetch(PDO::FETCH_ASSOC)) {
                                 echo "<option value='".$row["teamID"]."'".($row["teamID"]==$teamID1 ? " selected" : "").">".$row["teamname"]."</option>";
                             }
                        ?>
                    </select>
                </td>
                <td>Team 2:</td>
                <td>
                    <select name="teamID2">
                        <option <?=($teamID2==null ? " selected" : "")?> value="-1">---</option>
                        <?php
                             $db_teams = $pdo->prepare("SELECT teamID,teamname FROM team");
                             $db_teams -> execute();
                             while ($row = $db_teams -> fetch(PDO::FETCH_ASSOC)) {
                                 echo "<option value='".$row["teamID"]."'".($row["teamID"]==$teamID2 ? " selected" : "").">".$row["teamname"]."</option>";
                             }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Punkte 1:</td>
                <td><input type="number" name="punkte1" value="<?=$punkte1?>"></td>
                <td>Punkte 2:</td>
                <td><input type="number" name="punkte2" value="<?=$punkte2?>"></td>
            </tr>
            <tr>
                <td>Handeingriffe 1:</td>
                <td><input type="number" name="handeingriffe1" value="<?=$handeingriffe1?>"></td>
                <td>Handeingriffe 2:</td>
                <td><input type="number" name="handeingriffe2" value="<?=$handeingriffe2?>"></td>
            </tr>
            <tr>
                <td>Strafpunkte 1:</td>
                <td><input type="number" name="strafpunkte1" value="<?=$strafpunkte1?>"></td>
                <td>Strafpunkte 2:</td>
                <td><input type="number" name="strafpunkte2" value="<?=$strafpunkte2?>"></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    <select name="status">
                        <option value="0" <?=($status==0 ? " selected" : "")?>>ausstehend</option>
                        <option value="1" <?=($status==1 ? " selected" : "")?>>läuft</option>
                        <option value="2" <?=($status>=2 ? " selected" : "")?>>abgeschlossen</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>fixiert:</td>
                <td><input type="checkbox" name="fixiert" <?=($fixiert==1 ? " checked" : "")?>></td>
            </tr>
            <tr>
                <td>Uhrzeit:</td>
                <td><input type="time" name="zeit" value="<?=$zeit?>"></td>
            </tr>
            <tr>
                <td>Punkte geändert:</td>
                <td><input type="checkbox" name="punktegeaendert" <?=($punktegeaendert==1 ? " checked" : "")?>></td>
            </tr>
            <tr>
                <td>Uhrzeit geändert:</td>
                <td><input type="checkbox" name="zeitgeaendert" <?=($zeitgeaendert==1 ? " checked" : "")?>></td>
            </tr>
		</tbody>
	</table>
	<?php if(isset($_POST["addspiel"])): ?>
		<input type="submit" name="add" value="Spiel erstellen">
	<?php else: ?>
		<input type="submit" name="change" value="Spiel bearbeiten">
	<?php endif; ?>
</form>