<?php

include('../config/config.php');
$vue = 'showArticles.phtml';
$imagePath = 'http://dimitriywd.sites.pixelsass.fr/PHP/Exercices/php1-5-blog/img/uploads/';

try{  

    $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des articles depuis la BDD
    $sth = $dbh->prepare('SELECT * FROM b_article');
    $sth->execute();
    $articles = $sth->fetchAll(PDO::FETCH_ASSOC);

}

catch(PDOException $e){
    echo 'Une erreur s\'est produite : '.$e->getMessage();
}

include('tpl/layout.phtml');