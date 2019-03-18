<?php

include('../config/config.php');

$vue = 'addUser.phtml';

$userEmail = '';
$userFirstname= '';
$userLastname = '';
$userPassword = '';
$userPasswordConfirm = '';
$userRole =  '';
$pageTitle = 'Ajouter un utilisateur';



//  Si la variable "editUser" n'est pas déclarée (dans editUser.php), alors on est en mode "Création"
//if (!isset($editUser)) {

    try{  
    
        // Connexion à la BDD  
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupération des emails de la BDD
        $sth = $dbh->prepare('SELECT u_email FROM b_user');
        $sth->execute();
        $userEmails = $sth->fetchAll(PDO::FETCH_ASSOC);
    
         
        // Enregistrement des valeurs du formulaire
        if(array_key_exists('userEmail', $_POST))
        {
            $userEmail = $_POST['userEmail'];
            $userFirstname= $_POST['userFirstname'];
            $userLastname = $_POST['userLastname'];
            $userPassword = $_POST['userPassword'];
            $userPasswordConfirm = $_POST['userPasswordConfirm'];
            $userRole = $_POST['userRole'];
    
            // On vérifie si l'email existe déjà dans la BDD
            foreach($userEmails as $Email_DB)
            {
                if($userEmail == $Email_DB['u_email'])
                {
                    echo 'check2';
                    $duplicateEmail=''; // Si oui, on initialise une variable. Ce qui va générer un warning à l'utilisateur
                }
            }
              
              
            // On vérifie la correspondance entre le mot de passe et sa confirmation
            if($userPassword != $userPasswordConfirm) {
              $pwdNotSame = '';
            }
            
        
        
            if ($userRole !='' && $userFirstname!='' && $userLastname !='' && $userEmail !='' && $userPassword !='') 
            {
                //Hachage du mot de passe
                $pwdHash=(password_hash($userPassword, PASSWORD_DEFAULT));
                
                // Pour vérifier si le mdp correspond bien à un hachage : utiliser "password_verify"
                
                //Enregistrement des données en BDD
                $sth = $dbh->prepare ('INSERT INTO b_user (u_email, u_firstname, u_lastname, u_password, u_role)
                  VALUES (:u_email, :u_firstname, :u_lastname, :u_password, :u_role)');
                $sth->bindValue(':u_email', $userEmail, PDO::PARAM_STR);
                $sth->bindValue(':u_firstname', $userFirstname, PDO::PARAM_STR);
                $sth->bindValue(':u_lastname', $userLastname, PDO::PARAM_STR);
                $sth->bindValue(':u_password', $pwdHash, PDO::PARAM_STR);
                $sth->bindValue(':u_role', $userRole, PDO::PARAM_STR);
                $sth->execute();
    
                $userAdded ='';
                header('Location:userList.php');
                exit();
            }
            
            else {
                $emptyFields = '';
            }
    
        }
    
    }
            
    catch(PDOException $e){
      echo 'Une erreur s\'est produite : '.$e->getMessage();
    }
//}

/*
// Sinon on est en mode "Edition"   
else {
    $pageTitle = 'Modifier un utilisateur'; // Modif du titre de la page
    
    try { 
        if (array_key_exists('id', $_GET)){
          $userId = $_GET['id']; 
      
            // Connexion à la BDD  
            $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Récupération des données utilisateurs de la BDD
            $sth = $dbh->prepare('SELECT * FROM b_user');
            $sth->execute();
            $users = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            // Enregistrement des valeurs du formulaire
            if(array_key_exists('id', $_GET))
            {
                $userEmail = $users [$userId]['u_email'];
                $userFirstname= $users [$userId]['u_firstname'];
                $userLastname = $users [$userId]['u_lastname'];
                $userRole = $users [$userId]['u_role'];
                
                /*if(array_key_exists('userEmail', $_POST))
                {
                    // On vérifie si l'email existe déjà dans la BDD
                    foreach($users as $Email_DB)
                    {
                        if($userEmail == $Email_DB['u_email'])
                        {
                            echo $Email_DB['u_email'];
                            $duplicateEmail=''; // Si oui, on initialise une variable. Ce qui va générer un warning à l'utilisateur
                        }
                    }
                }
        
                
            }
        }
    }
            
    catch(PDOException $e){
      echo 'Une erreur s\'est produite : '.$e->getMessage();
    }
    
}*/






include('tpl/layout.phtml');
