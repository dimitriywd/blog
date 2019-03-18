<?php

include('../config/config.php');

$vue = 'addArticle.phtml';

$articleData = [];

$articleAuthor = '';
$articleContent = '';
$articleDate = '';
$articleTime = '';
$date = '';
$articleCategory = '';
$articleTitle = '';
$pictureArticle ='';

$imagePath = 'http://dimitriywd.sites.pixelsass.fr/PHP/Exercices/php1-5-blog/img/uploads/';


//Titre de la page par défaut
$pageTitle = 'Créer un article';



  /****************************************************************************************/
  /********************* Récupération des catégories depuis la BDD ************************/           
  try{  
  
      $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      
      // Récupération des catégories de la BDD
      $sth = $dbh->prepare('SELECT * FROM b_category');
      $sth->execute();
      $categories = $sth->fetchAll(PDO::FETCH_ASSOC);
      
  
      // Enregistrement des valeurs du formulaire
      if(array_key_exists('articleTitle', $_POST) == true)
      {
          $articleTitle = $_POST['articleTitle'];
          $articleAuthor = 1; //$_POST['articleAuthor'];
          $articleCategory = $_POST['articleCategory'];
          $articleDate = $_POST['articleDate'];
          $articleTime = $_POST['articleTime'];
          $date = new DateTime($articleDate .$articleTime);
          $articleContent = $_POST['articleContent'];
          
          if ($articleTitle !=='')
          {
  
          // Importation des images
            if(array_key_exists('articleImage', $_FILES)) { // Les inputs de type "files" (dans le HTML) renvoient des données dans le tableau "FILES"
              // var_dump($_FILES['articleImage']); // Ce tableau renvoie les données "name", "type", "tmp_name" (dossier temporaire ou sera placée l'image), "error" (0 = pas d'erreur) et "size".
            }
            /* Récupérer l'image et la déplacer ! */
            if ($_FILES["articleImage"]["error"] == UPLOAD_ERR_OK) { // On récupère la valeur retournée par le formulaire, et si il n'y a pas d'erreur on continue 
                $tmp_name = $_FILES["articleImage"]["tmp_name"]; // On récupère le nom de l'image (articleImage) et on va lui donner un nouveau nom (tmp_name) pour éviter les doublons
                // basename() peut empêcher les attaques de système de fichiers;
                // la validation/assainissement supplémentaire du nom de fichier peut être approprié
                $pictureArticle = uniqid().'-'.basename($_FILES["articleImage"]["name"]); // On génère un id unique. On prend l'ancien nom, on ajoute un id et on l'ajoute à l'ancien nom pour créer le nouveau nom.
                move_uploaded_file($tmp_name, UPLOADS_DIR.$pictureArticle); // On déplace le fichier du dossier temporaire vers le dossier définitif "UPLOADS_DIR" qui est définit dans le fichier "config.php"
            } 
            
            else {
                $errorForm = 'Une erreur s\'est produite lors de l\'upload de l\'image !';
            } 
          // Fin importation des images

            //Enregistrement des données en BDD
            $sth = $dbh->prepare ('INSERT INTO b_article (a_author, a_title, b_category_c_id, a_date_published, a_content, a_picture)
                VALUES (:a_author, :a_title, :b_category_c_id, :a_date_published, :a_content, :a_picture)');
            $sth->bindValue(':a_author', $articleAuthor, PDO::PARAM_STR);
            $sth->bindValue(':a_title', $articleTitle, PDO::PARAM_STR);
            $sth->bindValue(':b_category_c_id', $articleCategory, PDO::PARAM_INT);
            $sth->bindValue(':a_date_published', $articleDate);
            $sth->bindValue(':a_content', $articleContent, PDO::PARAM_STR);
            $sth->bindValue(':a_picture', $pictureArticle, PDO::PARAM_STR);
            //$sth->execute();
            
          var_dump($_POST);
          }    
          
      }

  }
  
  catch(PDOException $e){
      echo 'Une erreur s\'est produite : '.$e->getMessage();
  }





include('tpl/layout.phtml');

