<?php


$PAGES = new Page('login.html');
$PAGES -> setContent(file_get_contents('dev/login.html'));
$PAGES -> setMeta([
    'thumbnail'=>'view/images/SAM_0383.JPG',
]);
$PAGES->save();

$PAGES = new Page('accueil.html');
$PAGES -> setContent(file_get_contents('dev/accueil.html'));
$PAGES -> setMeta([
    'draft'=>0,
    'thumbnail'=>'view/images/SAM_0383.JPG',
]);
$PAGES->save();


$PAGES = new Page('404.html');
$PAGES -> setContent("Désolé, ce contenu n'existe pas, où a été déplacé.");
$PAGES -> setMeta([
    'thumbnail'=>'view/images/SAM_0383.JPG',
]);
$PAGES->save();
$PAGES = new Page('403.html');
$PAGES -> setContent("Désolé, vous n'avez pas accès à ce contenu.");
$PAGES -> setMeta([
    'thumbnail'=>'view/images/SAM_0383.JPG',
]);
$PAGES->save();
