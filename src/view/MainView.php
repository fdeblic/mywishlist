<?php
namespace mywishlist\view;

  class MainView extends GlobalView {

    function __construct() {
      parent::__construct();
    }

    /**
     *Génère le contenu HTML pour afficher la page d'accueil
     */
    function render() {
        $content = "<h1>Bienvenue en terre sainte.</h1>\n";
        $content .=
"<p>
    Vous souhaitez créer une liste de souhaits pour un anniversaire ou un mariage ?
    Ou tout événement vous tenant à coeur ?
    Vous vous trouvez au bon endroit !
</p>
<p>
    Les listes actuelles sont disponibles en cliquant sur l'onglet <i>Listes</i>.
    Vous pouvez avoir accès aux listes publiques,
    mais également aux listes privées auxquelles vous auriez été invités.
</p>
<p>
    Vous pouvez bien évidemment créer vos propres listes et les partager.
    Pour cela, merci de vous connecter.
</p>

<p>
    La liste des différents créateurs de listes publiques est diponible en cliquant sur
    l'onglet <i>Créateurs</i>.
</p>
 <div align=\"center\"><i>La fête n'attend plus que vous !</i</div>
"
;
        $content = str_replace ("\n", "\n  ", $content);
        $this->addContent($content);
        parent::render();
    }
 }
