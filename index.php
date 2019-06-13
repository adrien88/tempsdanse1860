<?php

/**
*  To debug and dev
*/
ini_set('display_errors','1');


/**
*   Load config
*/
$CONFIG = parse_ini_file('config.ini',1);
if (!isset($_GET) or empty($_GET)){
    header("location:?page=".$CONFIG['site']['landing']);
    exit;
}



/**
*   Includes files && Class autoloader
*/
include 'includes/functions.php';
spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});
include 'dev/devAuto.php';


## créer utiisateur
$USER = new User();

$SITE = $CONFIG['site'];
?>

<!DOCTYPE html>
<html lang="<?=$SITE['lang']?>" dir="ltr">
    <head>
        <meta charset="<?=$SITE['charset']?>">
        <title><?=$title?> <?=$SITE['title']?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="view/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="view/css/main.css">
    </head>

    <body id="pbody">

        <?php
            if($_GET['page'] != 'dashboard.php'){
        ?>
        <header class="container-fluid">
            <div class="row">
                <div class="header container">
                    <h1>Temps danse 1860</h1>
                    <p>Association de la loi de 1901</p>
                </div>
            </div>
        </header>

        <nav class="container-fluid">
            <div class="row">
                <div class="navbar container">
                    <?php

                    ?>
                    navabar ici
                </div>
            </div>
        </nav>
        <main>
            <div class="row">
                <div class="container">
                    <?php
                        Page::run();
                    ?>
                </div>
            </div>
        </main>

        <footer class="container-fluid">
            <div class="row footertitle">
                <div class="container">
                    <h2>Temps Danse 1860 © 2019</h2>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 footerblock">
                            <h3>Réseaux sociaux</h3>
                            <ul>
                                <li><a href="#">Facebook</a></li>
                                <li><a href="#">Twitter</a></li>
                            </ul>

                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 footerblock">
                            <h3>Mentions Légales</h3>
                            <p>Conformément à la RGPD : le site...</p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 footerblock">
                            <h3>Plans du site</h3>
                            <ul>
                                <li><a href="?page=accueil.html">Accueil</a></li>
                                <li><a href="?page=mentions.html">Mention Légales</a></li>
                                <li><a href="?page=contact.html">Contact</a></li>
                                <hr>
                                <li><a href="?page=login.html">Login</a></li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <?php
            } else {
        ?>




        <?php

            }
        ?>

    </body>
</html>
