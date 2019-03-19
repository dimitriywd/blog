<?php
session_start();
include('../config/config.php');

$vue = 'addUser.phtml';

$userEmail = '';
$userFirstname= '';
$userLastname = '';
$userPassword = '';
$userPasswordConfirm = '';
$userRole =  '';
$pageTitle = 'Ajouter un utilisateur';


$editUser=true; // Cette valeur définit qu'on est en mode édition


    $pageTitle = 'Modifier un utilisateur'; // Modif du titre de la page
    
    try { 
        if (array_key_exists('id', $_GET)){
            $userId = $_GET['id']; 
            // Connexion à la BDD  
            $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Récupération des données utilisateurs de la BDD
            $sth = $dbh->prepare('SELECT * FROM b_user WHERE u_id=:id');
            $sth->bindValue('id',$userId,PDO::PARAM_INT);
            $sth->execute();
            $user = $sth->fetch(PDO::FETCH_ASSOC);
            
            $userEmail = $user['u_email'];
            $userFirstname = $user['u_firstname'];
            $userLastname = $user['u_lastname'];
            $userRole = $user['u_role'];
                
        }
        
        // Enregistrement des valeurs du formulaire
        if(array_key_exists('userId', $_POST))
        {
            $userId = $_POST['userId'];
            $userEmail = $_POST['userEmail'];
            $userFirstname = $_POST['userFirstname'];
            $userLastname = $_POST['userLastname'];
            $userRole = $_POST['userRole'];
            
            // Connexion à la BDD  
            $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //Mise à jour des données en BDD
            $sth = $dbh->prepare ('UPDATE b_user SET u_firstName=:u_firstName, u_lastName=:u_lastName, u_email=:u_email, u_role=:u_role WHERE u_id = :u_id' );
            
            
            $sth->bindValue(':u_firstName', $userFirstname, PDO::PARAM_STR);
            $sth->bindValue(':u_lastName', $userLastname, PDO::PARAM_STR);
            $sth->bindValue(':u_email', $userEmail, PDO::PARAM_STR);
            $sth->bindValue(':u_role', $userRole, PDO::PARAM_STR);
            $sth->bindValue(':u_id', $userId, PDO::PARAM_INT);
            $sth->execute();

        }
    }
            
    catch(PDOException $e){
      echo 'Une erreur s\'est produite : '.$e->getMessage();
    }
    




include('tpl/layout.phtml');