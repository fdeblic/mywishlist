<?php
namespace mywishlist\view;

  class MainView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    /*function render() {
        $content = "<h1>Bienvenue en terre sainte.</h1>";
        $content .= "<p>
            Notre catalogue est à votre disposition.
            Vous pouvez avoir accès aux listes publiques,
            mais également aux listes privées auxquelles vous auriez été invités.
            Pour cela, merci de vous connecter.
        </p>";
        $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
        parent::render();
    }*/

    /* Génère le contenu HTML pour la page d'upload
    * d'une image */
    function generateImgForm(){
        $content = "<h1> Uploadage d'image </h1>";
        $form = " <form action='".$_SESSION['app']->urlFor('pot_addImg_post')."' method='POST' enctype='multipart/form-data'>
            <p>Sélectionnez une image à uploader : </p>
            <input type='file' name='image' id='image'>
            <input type='submit' value='Upload Image' name='submit'>
        </form>";
        $_SESSION['content'] = $form;
        parent::render();
    }

 }
