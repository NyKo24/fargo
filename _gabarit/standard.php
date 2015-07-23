<!DOCTYPE>
<html>
    <head>
        <? require BASE_REP . "_gabarit/inc_head.php"; ?>
    </head>
    <?if (isset($_MYFILE))
    	foreach ($_MYFILE as $valeur)
    		echo $valeur;
    if (isset($_MYPLUGIN))
    	foreach ($_MYPLUGIN as $valeur)
    		require $valeur 
    ?>
    <body>
        <header>
            <? require BASE_REP . "_gabarit/inc_entete.php"; ?>
            <? require BASE_REP . "_gabarit/inc_menu.php"; ?>
        </header>
        <div id="contenu" class="container">
            <? require $this->vue; ?>
        </div>
        <footer>
            <? require BASE_REP . "_gabarit/inc_pied.php"; ?>
        </footer>
    </body>
</html>
