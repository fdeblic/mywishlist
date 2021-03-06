# Projet MyWishlist

![Screenshot](screenshot.jpg)

## Descriptif du projet

MyWishList.app est une application en ligne pour créer, partager et gérer des listes de cadeaux.
L'application permet à un utilisateur de créer une liste de souhaits à l'occasion d'un événement
particulier (anniversaire, fin d'année, mariage, retraite …) et lui permet de diffuser cette liste de
souhaits à un ensemble de personnes concernées. Ces personnes peuvent alors consulter cette liste
et s'engager à offrir 1 élément de la liste. Cet élément est alors marqué comme réservé dans cette
liste.

## Membres du groupe
* Élise Moreau
* Loïc Spacher
* François de Blic

## Suivi du projet

Google Doc : [tableau de fonctionnalités + UML ](https://docs.google.com/spreadsheets/d/19UPHj1gMIqu7Sq7Orh9uGofGDRxGXhC_KIhhlpRpf_Y/edit?usp=sharing)

Rendu web : [Site MyWishList](http://fdeblic.ddns.net/wishlist/)

## Installation
* Cloner le dépôt :
```
  git clone https://github.com/fdeblic/mywishlist/
```
* Importer le fichier mywishlist.sql se trouvant dans src à votre base de données
* Créer un dossier conf dans src
    * Créer le fichier *db.conf.ini* dans ce dossier
    * Modifier le pour qu'il corresponde aux informations de votre base de données :
```
  driver=mysql
	host=localhost
	database=db_name
	username=db_user
	password=db_password
	charset=utf8
	collation=utf8_unicode_ci
	prefix=  
  ```

* Télécharger le fichier .htaccess et le placer à la racine du projet:
[.htaccess](https://drive.google.com/file/d/1FyX5qk8CnRWy90kXeUxuqFNHbE_b_SUI/view?usp=sharing)
* Le site est prêt, vous pouvez l'utiliser

## Descriptif des fonctionnalités réalisées##
* Gestion des comptes :
    * Créer un compte,
    * S'authentifier,
    * Modifier son compte,
    * Créer un compte participant : il n'y a pas nécessité d'être connecté pour participer à une cagnotte ou pour réserver un item. Il suffit de fournir un pseudo et éventuellement un message,
    * Afficher la liste des créateurs,
    * Supprimer son compte.
* Gestion des listes :
    * Créer une liste,
    * Modifier les informations générales d'une de ses listes,
    * Rendre une liste publique,
    * Afficher les listes de souhaits publiques,
    * BONUS: Supprimer une liste.
* Gestion des items :
    * Ajouter un item,
    * Modifier un item,
    * Supprimer un item
* Participation à une liste :
    * Afficher une liste de souhaits,
    * Afficher un item d'une liste,
    * Réserver un item,
    * Partager une liste,
    * Consulter les réservations d'une de ses listes avant échéance,
    * Créer une cagnotte sur un item,
    * Participer à une cagnotte.
* Gestion des messages :
    * Ajouter un message avec sa réservation : le message ne peut être ajouter qu'au moment de la réservation, pas après. Il ne peut être modifié,
    * Ajouter un message sur une liste : un message ne peut être modifié ni supprimé,
    * Consulter les réservations et messages d'une de ses listes après échéance.
* Gestion des images :
    * Rajouter une image à un item,
    * Modifier une image d'un item,
    * Supprimer une image d'un item,
    * Uploader une image.
