<?php
	require_once(__DIR__."/lib/db_connection.php");
	
	define("P_BLOCK","block");
	define("P_SPIEL","spiel");
	define("P_TEAM","team");
	define("P_BEAMER","beamer");
	define("P_ANZEIGE1","anzeige1");
	define("P_ANZEIGE2","anzeige2");
	define("P_ABLAUF","ablauf");
	define("P_UEBERSICHT","uebersicht");

	$abschnitt = isset($_GET['abschnitt']) ? $_GET['abschnitt'] : P_UEBERSICHT;
	if (!isset($_GET['abschnitt'])) header('location: ?abschnitt='.P_UEBERSICHT);
?>
<html>
    <head>
        <meta charset="UTF-8">
		<script src="lib/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="lib/stylesheet.css">
		<?php if($abschnitt==P_UEBERSICHT): ?>
			<link rel="stylesheet" href="inc/uebersicht.css">
		<?php endif; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.jqChart.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.jqRangeSlider.css">
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.21/themes/smoothness/jquery-ui.css">
		<script src="lib/jqChart/js/jquery.jqChart.min.js" type="text/javascript"></script>
		<script src="lib/jqChart/js/jquery.jqRangeSlider.min.js" type="text/javascript"></script>
		<script src="lib/jqChart/js/jquery.mousewheel.js" type="text/javascript"></script>
    </head>
    <body class="no-overflow-horizontal">
        <nav>
			<?php
				if($abschnitt==P_ANZEIGE1) {
					echo '<span class="current">Präsentation</span>';
				}
				else {
					echo'<a href="?abschnitt='.P_ANZEIGE1.'">Präsentation</a>';
				}
				if($abschnitt==P_ANZEIGE2) {
					echo '<span class="current">Spielstand</span>';
				}
				else {
					echo'<a href="?abschnitt='.P_ANZEIGE2.'">Spielstand</a>';
				}
				if($abschnitt==P_BEAMER) {
					echo '<span class="current">Beamer</span>';
				}
				else {
					echo'<a href="?abschnitt='.P_BEAMER.'">Beamer</a>';
				}
				if($abschnitt==P_UEBERSICHT) {
					echo '<span class="current">Übersicht</span>';
				}
				else {
					echo '<a href="?abschnitt='.P_UEBERSICHT.'">Übersicht</a>';
					if(($abschnitt!=P_BEAMER) and ($abschnitt!=P_ANZEIGE1) and ($abschnitt!=P_ANZEIGE2)) {
						echo '<span class="current">';
						switch($abschnitt) {
							case P_TEAM: echo 'Team'; break;
							case P_BLOCK: echo 'Block'; break;
							case P_SPIEL: echo 'Spiel'; break;
							case P_ABLAUF: echo 'Ablauf'; break;
						}
						echo ' bearbeiten</span>';
					}
				}
			?>
			<span id="line"></span>
        </nav>
        <main>
            <?php
                switch($abschnitt) {
					case P_UEBERSICHT: include(__DIR__.'/inc/uebersicht.php'); break;
                    case P_TEAM: include(__DIR__.'/inc/team.php'); break;
                    case P_BLOCK: include(__DIR__.'/inc/block.php'); break;
                    case P_SPIEL: include(__DIR__.'/inc/spiel.php'); break;
					case P_BEAMER: include(__DIR__.'/inc/beamer.php'); break;
					case P_ABLAUF: include(__DIR__.'/inc/ablauf.php'); break;
					case P_ANZEIGE1: include(__DIR__.'/anzeige1.php'); break;
					case P_ANZEIGE2: include(__DIR__.'/anzeige2.php'); break;
                }
            ?>
        </main>
</html>
