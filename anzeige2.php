<?php
	require_once(__DIR__."/lib/db_connection.php");
	$db_connection = new DB_Connection();
	$pdo = $db_connection -> getPDO();
?>
<html>
    <head>
        <meta charset="UTF-8">
		<script src="lib/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="lib/stylesheet.css">
    </head>
    <body class="no-overflow">
		<!--<div class="no-cursor-element"></div>-->
        <section id="naechstespiele" class="no-overflow">
            <?php
				include(__DIR__."/inc/naechstespiele.php");
            ?>
        </section>
		<section id="text">
			<?php
				include(__DIR__."/inc/spielstand.php");
			?>
		</section>
    </body>
</html>
