<?php
    include_once("lib/message.php");
    if(isset($_GET["state"]) and ($_GET["state"]<6)) {
		$state = $_GET["state"];
	}
	else {
		$state = 0;
		header("location: ?state=0");
    }
    require(__DIR__."/lib/db_connection.php");

    if(isset($_POST["settings"])) {
        $xml = simplexml_load_file("settings.xml");
        $xml->startzeit = $_POST["wettbewerbsbeginn"];
        $xml->spieldauer = $_POST["spieldauer"];
        $xml->spielpause = $_POST["spielpause"];
        $xml->strafe = $_POST["strafpunkte"];
        $xml->handeingriff = $_POST["handeingriffe"];
        $xml->bewertungslimit = $_POST["bewertungslimit"];
        $xml->asXml("settings.xml");
        /*
        $dom = new DOMDocument();
        $dom -> load("settings.xml");
        $wettbewerb = $dom -> documentElement -> getElementsByTagName("wettbewerb") ->item(0);
        $wettbewerb -> getElementsByTagName("wettbewerbsbeginn") -> item(0) -> textContent = $_POST["wettbewerbsbeginn"];
        $wettbewerb -> getElementsByTagName("strafpunkte") -> item(0) -> textContent = $_POST["strafpunkte"];
        $wettbewerb -> getElementsByTagName("handeingriffe") -> item(0) -> textContent = $_POST["handeingriffe"];
        $wettbewerb -> getElementsByTagName("spieldauer") -> item(0) -> textContent = $_POST["spieldauer"];
        $wettbewerb -> getElementsByTagName("spielpause") -> item(0) -> textContent = $_POST["spielpause"];
        $wettbewerb -> getElementsByTagName("bewertungslimit") -> item(0) -> textContent = $_POST["bewertungslimit"];
        $dom -> save("settings.xml");
        */
    }
    if(isset($_POST["einlesen"])) {
        foreach (explode("\n",trim($_POST["namen"])) as $line) {
            $line = trim($line);
            $db_teamdazu = $pdo -> prepare("INSERT HIGH_PRIORITY INTO team (teamname) VALUES (:teamname)");
            $db_teamdazu -> execute(array(":teamname"=>$line));
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">

		<script src="lib/jquery-3.1.1.min.js"></script>
        <script src="lib/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="lib/stylesheet.css">
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.21/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
    </head>
    <body>
        <?php if($state==0): ?>
            <h1>Setup - Allgemeines</h1>
            <?php
                drop_message(1,false,0,"Hier lassen sich einige für den Wettbewerb relevante grundlegende Eigenschaften festlegen.");
                $xml = simplexml_load_file("settings.xml");
            ?>
            <form action="?state=1" method="post">
                <table>
                    <tr>
                        <td>Wettbewerbsbeginn</td>
                        <td><input type="time" name="wettbewerbsbeginn" maxlength="5" value="<?=$xml->startzeit?>"></td>
                    </tr>
                    <tr>
                        <td>Strafpunkte</td>
                        <td><input type="number" name="strafpunkte" maxlength="1" value="<?=$xml->strafe?>"></td>
                    </tr>
                    <tr>
                        <td>Handeingriffe</td>
                        <td><input type="number" name="handeingriffe" maxlength="1" value="<?=$xml->handeingriff?>"></td>
                    </tr>
                    <tr>
                        <td>Spieldauer (in s)</td>
                        <td><input type="number" name="spieldauer" maxlength="1" value="<?=$xml->spieldauer?>"></td>
                    </tr>
                    <tr>
                        <td>Spielpause (in s)</td>
                        <td><input type="number" name="spielpause" maxlength="1" value="<?=$xml->spielpause?>"></td>
                    </tr>
                    <tr>
                        <td>Bewertungslimit</td>
                        <td>
                            <select name="bewertungslimit">
                                <option value=""<?=(empty($xml->bewertungslimit) ? " selected" : "")?>>alle</option>
                                <optgroup label="Anzahl">
                                    <?php
                                        for($i=1;$i<=5;$i++) {
                                            echo "<option name=\"".$i."\"".(($xml->bewertungslimit == $i) ? " selected" : "").">".$i."</option>\n";
                                        }
                                    ?>
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <input type="submit" value="Weiter" name="settings">
            </form>
        <?php elseif($state==1): ?>
            <h1>Setup - Datenbank</h1>
            <?php
                drop_message(1,false,0,"Richten Sie bitte zuerst die Datenbank ein, bevor Sie hier Daten eintragen. (siehe beiliegende SQL-Datei)");
            ?>
            <form action="?state=2" method="post">
                <input type="submit" value="Weiter">
            </form>
        <?php elseif($state==2): ?>
            <h1>Setup - Teams</h1>
            <?php
                drop_message(1,false,0,"Fügen Sie hier die Namen aller Teams ein. (je ein Name pro Zeile)");
            ?>
            <form method="post">
                <textarea rows="16" cols="30" name="namen"></textarea>
                <br>
                <input type="submit" name="einlesen" value="Einlesen">
            </form>
            <form action="?state=3" method="post">
                <input type="submit" value="Weiter">
            </form>
        <?php elseif($state==3): ?>
            <h1>Setup abgeschlossen</h1>
            <!--Spiele generieren-->
            <?php
                drop_message(1,false,0,"Die einzelnen Zeitabschnitte und Blöcke sind im Abschnitt Ablauf im \"Hauptprogramm\" zu erstellen.");
            ?>
        <?php endif; ?>
        <script>
            $("input[type=submit]").button();
        </script>
    </body>
</html>