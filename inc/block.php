<?php
    $db_nextfreeID = $pdo -> prepare("SELECT * FROM block");
    $db_nextfreeID -> execute();
    $blockID = sizeof($db_nextfreeID -> fetchAll());
	if(isset($_POST["block"])) $blockID = $_POST["block"];
    $bezeichnung = "";
    $art = 1;
    $zeit = "";
    $dauer = "";
    $puffer = "";
    $status = "";
	$sichtbar_anzeige = 0;

    /*if(isset($_POST["add"])) {
        $db_add = $pdo -> prepare("INSERT INTO block (art,status,manuell,sichtbar_anzeige) VALUES (:art,:status,:manuell,:sichtbar_anzeige)");
        $db_add -> execute(array(":art"=>$_POST["art"],":status"=>$_POST["status"],":manuell"=>$_POST["manuell"],":sichtbar_anzeige"=>(isset($_POST["sichtbar_anzeige"]) ? 1 : 0)));
        include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
    }*/
    if(isset($_POST["change"])) {
        $db_change = $pdo -> prepare("UPDATE block SET art=:art,status=:status,manuell=:manuell,sichtbar_anzeige=:sichtbar_anzeige WHERE blockID=:blockID");
        $db_change -> execute(array(":blockID"=>$_POST["blockID"],":art"=>$_POST["art"],":status"=>$_POST["status"],":manuell"=>$_POST["manuell"],":sichtbar_anzeige"=>(isset($_POST["sichtbar_anzeige"]) ? 1 : 0)));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
    }
    else if(isset($_POST["changeblock"])) {
        $db_bloecke = $pdo -> prepare("SELECT blockID,art,status,sichtbar_anzeige,manuell,ablauf.bezeichnung,ablauf.zeit,ablauf.dauer,ablauf.puffer FROM block JOIN ablauf ON ablauf.ablaufID=block.ablaufID WHERE blockID=:blockID");
        $db_bloecke -> execute(array(":blockID"=>$_POST["block"]));
        $db_block = $db_bloecke -> fetch(PDO::FETCH_ASSOC);
        $blockID = $db_block["blockID"];
        $art = $db_block["art"];
        $status = $db_block["status"];
        $sichtbar_anzeige = $db_block["sichtbar_anzeige"];
        $bezeichnung = $db_block["bezeichnung"];
        $manuell = $db_block["manuell"];
        $zeit = $db_block["zeit"];
        $dauer = $db_block["dauer"];
        $puffer = $db_block["puffer"];
    }
	else if(isset($_POST["startblock"])) {
		$db_bloecke = $pdo -> prepare("UPDATE block SET status=2 WHERE status=1");
		$db_bloecke -> execute();
		$db_bloecke = $pdo -> prepare("UPDATE block SET status=1 WHERE blockID=:blockID");
		$db_bloecke -> execute(array(":blockID"=>$_POST["block"]));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
	}
?>
<?php if(!isset($_POST["changeblock"])): ?>
	<h1>Block erstellen</h1>
<?php else: ?>
	<h1>Block bearbeiten</h1>
<?php endif; ?>
<form method="post">
	<input type="hidden" name="blockID" value="<?=$blockID?>">
    <table>
        <tbody>
            <tr>
                <td>Bezeichnung:</td>
                <td><input type="text" name="bezeichnung" value="<?=$bezeichnung?>" disabled></td>
            </tr>
            <tr>
                <td>Art:</td>
                <td>
                    <select name="art">
                        <option value="0" <?php echo (($art==0) ? " selected" : "");?>>Alleinspiele</option>
                        <option value="1" <?php echo (($art==1) ? " selected" : "");?>>normale Spiele</option>
                        <option value="2" <?php echo (($art==2) ? " selected" : "");?>>KO-Spiele</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    <select name="status">
                        <option value="0" <?php echo (($status==0) ? " selected" : "");?>>ausstehend</option>
                        <option value="1" <?php echo (($status==1) ? " selected" : "");?>>läuft</option>
                        <option value="2" <?php echo (($status==2) ? " selected" : "");?>>abgeschlossen</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Uhrzeit:</td>
                <td><input type="time" name="zeit" value="<?=$zeit?>" disabled></td>
            </tr>
            <tr>
                <td>Dauer:</td>
                <td><input type="number" name="dauer" value="<?=$dauer?>" disabled></td>
            </tr>
            <tr>
                <td>Puffer:</td>
                <td><input type="number" name="puffer" value="<?=$puffer?>" disabled></td>
            </tr>
            <tr>
                <td>Generierung der Spiele:</td>
                <td>
                    <select name="manuell">
                        <option value="0" <?php echo (($manuell==0) ? " selected" : "");?>>automatisch</option>
                        <option value="1" <?php echo (($manuell==1) ? " selected" : "");?>>manuell</option>
                    </select>
                </td>
            </tr>
			<tr>
                <td>in Anzeige sichtbar:</td>
                <td><input type="checkbox" name="sichtbar_anzeige" <?=($sichtbar_anzeige==1 ? "checked" : "")?>></td>
            </tr>
        </tbody>
    </table>
    <!--<?php if(isset($_POST["addblock"])): ?>
        <input type="submit" name="add" value="Block erstellen">
    <?php else: ?>-->
        <input type="submit" name="change" value="Block bearbeiten">
    <!--<?php endif; ?>-->
</form>