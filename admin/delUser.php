<?php
session_start();
include('../config/config.php');

$vue = 'userList.phtml';

try { 
    if (array_key_exists('id', $_GET)){
        $userId = $_GET['id']; 
        
        // Connexion à la BDD  
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Suppression es données en base selon l'ID
        $sth = $dbh->prepare ('DELETE FROM b_user WHERE u_id=:u_id');
        $sth->bindValue(':u_id', $userId, PDO::PARAM_INT);
        $sth->execute(); 
        
        header('Location:userList.php');
        exit();
    }

}
        
catch(PDOException $e){
  echo 'Une erreur s\'est produite : '.$e->getMessage();
}


include('tpl/layout.phtml');