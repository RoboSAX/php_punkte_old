<?php
    $db_nextfreeID = $pdo -> prepare("SELECT * FROM team");
    $db_nextfreeID -> execute();
	$teamID = sizeof($db_nextfreeID -> fetchAll())+1;
	$teamname = "";
	$anwesenheit = "";
	$platzierung = "";
	$post_anwesenheit = isset($_POST["anwesenheit"]) ? 1 : 0;

    if(isset($_POST["add"])) {
        $db_add = $pdo -> prepare("INSERT HIGH_PRIORITY INTO team (teamID,teamname,anwesenheit,platzierung) VALUES (:teamID,:teamname,:anwesenheit,:platzierung)");
        $db_add -> execute(array(":teamID"=>$_POST["teamID"],":teamname"=>$_POST["teamname"],":anwesenheit"=>$post_anwesenheit,":platzierung"=>$_POST["platzierung"]));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
    }
	else if(isset($_POST["change"])) {
        $db_change = $pdo -> prepare("UPDATE team SET teamname=:teamname,anwesenheit=:anwesenheit,platzierung=:platzierung WHERE teamID=:teamID");
        $db_change -> execute(array(":teamID"=>$_POST["teamID"],":teamname"=>$_POST["teamname"],":anwesenheit"=>$post_anwesenheit,":platzierung"=>$_POST["platzierung"]));
		include(__DIR__."/../spielgenerieren.php");
		header("Location: ?abschnitt=uebersicht");
	}
	else if(isset($_POST["changeteam"])) {
		$db_teams = $pdo -> prepare("SELECT * FROM team WHERE teamID=:teamID");
		$db_teams -> execute(array(":teamID"=>$_POST["team"]));
		$db_team = $db_teams -> fetch(PDO::FETCH_ASSOC);
		$teamID = $db_team["teamID"];
		$teamname = $db_team["teamname"];
		$anwesenheit = $db_team["anwesenheit"];
		$platzierung = $db_team["platzierung"];
	}
?>
<h1>Team</h1>
<form method="post">
    <table>
        <tbody>
            <tr>
                <td>TeamID:</td>
                <td><input type="number" name="teamID" value="<?=$teamID?>"></td>
            </tr>
            <tr>
                <td>Teamname:</td>
                <td><input type="text" name="teamname" value="<?=$teamname?>"></td>
            </tr>
            <tr>
                <td>Anwesenheit:</td>
                <td><input type="checkbox" name="anwesenheit" value="<?=$anwesenheit?>"></td>
            </tr>
            <tr>
                <td>Platzierung</td>
                <td><input type="number" name="platzierung" value="<?=$platzierung?>"></td>
            </tr>
        </tbody>
    </table>
	<?php if(isset($_POST["addteam"])): ?>
		<input type="submit" name="add" value="Team erstellen">
	<?php else: ?>
		<input type="submit" name="change" value="Team bearbeiten">
	<?php endif; ?>
</form>
