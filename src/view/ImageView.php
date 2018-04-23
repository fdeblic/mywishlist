<?php
namespace mywishlist\view;

  class ImageView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour la page d'upload
    * d'une image */
    function generateImgForm(){
        $content = "<h1> Téléchargement d'image </h1>";
        $content .= " <form action='".\Slim\Slim::getInstance()->urlFor('pot_addImg_post')."' method='POST' enctype='multipart/form-data'>
            <p>Sélectionnez une image à télécharger : </p>
            <input type='file' name='image' id='image'>
            <input type='submit' value='Upload Image' name='submit'>
        </form>";
        $_SESSION['content'] = $content;
        parent::render();
    }

    function renderUploadImage(){
        $content = "<p>Image correctement téléchargée.</p>";
        $_SESSION['content'] = $content;
        parent::render();
    }
 }
