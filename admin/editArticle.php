<?php
session_start();

include('../config/config.php');

$vue = 'addArticle.phtml';

$articleAuthor = '';
$articleContent = '';
$articleDate = '';
$articleTime = '';
$date = '';
$articleCategory = '';
$articleTitle = '';
$pictureArticle ='';

$imagePath = 'http://dimitriywd.sites.pixelsass.fr/PHP/Exercices/php1-5-blog/img/uploads/';

$editArt=true; // Cette valeur définit qu'on est en mode édition

// Sinon on est en mode "Edition"

  $pageTitle = 'Modifier un article'; // Modif du titre de la page
  
  try{ 
    if (array_key_exists('id', $_GET)){
      $articleId = $_GET['id']; 
      
      // Note : Faire fonction pour ci dessous car réutilisé dans 'showArticles.php'
      $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      // Récupération des articles depuis la BDD
      $sth = $dbh->prepare('SELECT * FROM b_article WHERE a_id=:id');
      $sth->bindValue('id',$articleId,PDO::PARAM_INT);
      $sth->execute();
      $article = $sth->fetch(PDO::FETCH_ASSOC);
      // Fin note
      
        // Récupération des catégories de la BDD
      $sth2 = $dbh->prepare('SELECT * FROM b_category');
      $sth2->execute();
      $categories = $sth2->fetchAll(PDO::FETCH_ASSOC);

 
      $articleTitle = $article['a_title'];
      $articleCategory = $article ['b_category_c_id'];
      $articleContent = $article ['a_content'];
      $articleDateTime = new DateTime($article['a_date_published']);
      $articleDate = $articleDateTime->format('Y-m-d');
      $articleTime = $articleDateTime->format('H:i');
      $articlePicture = $article['a_picture'];
      
      // Si il y a une image, alors on déclare une variable de contrôle
      if ($article['a_picture'] !== NULL) {     
        $Pic='' ;
      }

    } 
    
          
      //  MODIFIER L'ARTICLE SELON SON ID
      if(array_key_exists('articleTitle', $_POST) == true)
      {
        $articleId = $_POST['articleId'];
        $articleTitle = $_POST['articleTitle'];
        $articleAuthor = 1; //$_POST['articleAuthor'];
        $articleCategory = $_POST['articleCategory'];
        $articleDate = $_POST['articleDate'];
        $articleTime = $_POST['articleTime'];
        $date = new DateTime($articleDate .$articleTime);
        $articleContent = $_POST['articleContent'];
        
        // Connexion à la BDD
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        //Mise à jour des données en BDD
       $sth = $dbh->prepare ('UPDATE b_article SET a_title=:a_title, b_category_c_id=:b_category_c_id, a_date_published=:a_date_published, a_content=:a_content, a_picture=:a_picture WHERE a_id = :a_id' );
       
      
        $sth->bindValue(':a_title', $articleTitle, PDO::PARAM_STR);
        $sth->bindValue(':b_category_c_id', $articleCategory, PDO::PARAM_INT);
        $sth->bindValue(':a_date_published', $articleDate);
        $sth->bindValue(':a_content', $articleContent, PDO::PARAM_STR);
        $sth->bindValue(':a_picture', $pictureArticle, PDO::PARAM_STR);
        $sth->bindValue(':a_id', $articleId, PDO::PARAM_INT);
        $sth->execute();
      }

  }
  
  catch(PDOException $e){
      echo 'Une erreur s\'est produite : '.$e->getMessage();
  }


include('tpl/layout.phtml');