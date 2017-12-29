<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="lib/stylesheet.css">
        <style>
            main {
                width: 50%;
                float: left;
            }
        </style>
    </head>
    <body>
        <main>
            <?php
                require(__DIR__."/lib/db_connection.php");
                $db_connection=new DB_Connection();
                $pdo=$db_connection->getPDO();
                include(__DIR__.'/inc/team.php');
            ?>
        </main>
        <main>
            <?php
                include(__DIR__.'/inc/teamliste.php');
            ?>
        </main>
    </body>
</html>
