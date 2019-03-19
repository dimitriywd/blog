<?php
session_start();
include('../config/config.php');

$userEmail = '';
$userPassword = '';

try{  
  
    // Enregistrement des valeurs du formulaire
    if(array_key_exists('userEmail', $_POST))
    {
        $userEmail = $_POST['userEmail'];
        $userPassword = $_POST['userPassword'];
        var_dump($userPassword);
        
        // Connexion à la BDD  
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupération des emails de la BDD
        $sth = $dbh->prepare('SELECT `u_email`, `u_password` FROM `b_user` WHERE u_email=:u_email');
        $sth->bindValue(':u_email', $userEmail, PDO::PARAM_STR);
        $sth->execute();
        $userEmails = $sth->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($userPassword,$userEmails['u_password'])) // On vérifie que le mot de passe saisi correspond au mot de passe haché en BDD
        {
            header('Location:index.php');
            exit();
        }
        else
        {
            $badLogin='';
            header('Location:tpl/login.phtml');
            exit();
        }

       /* function formatArray($a){ // Fonction qui formate le tableau en 2 dimensions en un tableau en 1 dimension
            return $a['u_email'];
        };

        $userEmailsFormat = array_map('formatArray', $userEmails);
        
        if(in_array($userEmail,$userEmailsFormat) ){
            echo ('email trouvé');
            
        }*/

    }
}

catch(PDOException $e){
  echo 'Une erreur s\'est produite : '.$e->getMessage();
}




//include('tpl/login.phtml');