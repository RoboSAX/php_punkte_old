<?php
	function drop_message($type = 0,$die = false,$messageid = 0,$text = "Ein Fehler ist aufgetreten.") {
		switch($messageid) {
			case 1:
				
				break;
			case 2:
				
				break;
		}
		echo "<div class=\"";
		switch($type) {
			case 0:
				echo "anleitung";
				break;
			case 1:
				echo "hinweis";
				break;
			case 2:
				echo "warnung";
				break;
			case 3:
				echo "error";
				break;
		}
		echo "\"><center>";
		switch($type) {
			case 0:
				echo "Anleitung";
				break;
			case 1:
				echo "Hinweis";
				break;
			case 2:
				echo "Warnung";
				break;
			case 3:
				echo "Error";
				break;
		}
		echo "</center>".$text."</div>";
		if($die) die;
	}
?>