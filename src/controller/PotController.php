<?php
namespace mywishlist\controller;
use \mywishlist\view\ItemView as ItemView;
use \mywishlist\models\Item as Item;
use \mywishlist\models\PotParticipation as PotParticipation;

class PotController {

  /**
   *Participer à une cagnotte
   *@param $id id de l'item
   */
  public function participatePot($id) {
    $vue = new ItemView();
    $participation = new PotParticipation();
    $item = Item::where('id','=',$id)->first();
    $somme = PotParticipation::where('pot_id','=',$id)->sum('amount');

    if (!isset($_POST['name']) || strlen($_POST['name']) < 1)
      $vue->error('entrez un nom');

    if (!isset($_POST['amount']) || $_POST['amount'] < 1)
      $vue->error('entrez un montant de participation');

    if ($_POST['amount'] + $somme > $item->tarif)
      $vue->error('prix max atteint');

    $participation->pot_id = $id;
    $participation->name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);;
    $participation->amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    try {
      if ($participation->save())
        $vue->addHeadMessage("Votre participation de <b>$participation->amount €</b> a bien été enregistrée", "good");
      else
        $vue->addHeadMessage("Votre participation n'a pas pu être enregistrée", "bad");
    }
    catch (QueryException $e) {
      $vue->addHeadMessage("Une erreur est survenue à la sauvegarde...", "bad");
    }

    $vue->renderItem($item);
  }
}

?>
