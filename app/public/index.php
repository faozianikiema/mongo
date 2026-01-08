<?php
/**
 * Created by PhpStorm.
 * User: canals5
 * Date: 28/10/2019
 * Time: 16:16
 */

use MongoDB\Client;

require_once __DIR__ . "/../src/vendor/autoload.php";

$c = new Client("mongodb://mongo");
echo "connected to mongo <br>";
echo "------------ 1. afficher la liste des produits: numero, categorie, libelle------------------------ .<br>";
$db=$c->chopizza;
$collection=$db->produits;
$produits=$collection->find();
foreach ($produits as $produit){
    echo $produit->numero . "<br>";
    echo $produit->libelle . "<br>";
    echo $produit->description . "<br>";
    echo $produit->categorie . "<br>";
    echo "------------------------ .<br>";
}
echo "---------2. afficher le produit numéro 6, préciser : libellé, catégorie, description, tarifs--------------- .<br>";
$produit6=$collection->findOne(['numero'=>6]);
echo $produit6->numero . "<br>";
echo $produit6->libelle . "<br>";
echo $produit6->description . "<br>";
echo $produit6->categorie . "<br>";
foreach ($produit6->tarifs as $tarif){
    echo $tarif->taille . ":" . $tarif->tarif . "<br>";
};
echo "-----------3. liste des produits dont le tarif en taille normale est <= 3.0---------------<br>";
$produitTarif=$collection->find(['tarifs'=>['$elemMatch'=>['taille'=>'normale', 'tarif'=>['$lte'=>3.0]]]]);
foreach ($produitTarif as $produit){
    echo $produit->numero . "<br>";
    echo $produit->libelle . "<br>";
    echo $produit->description . "<br>";
    echo $produit->categorie . "<br>";
    foreach ($produit->tarifs as $tarif){
        if ($tarif->taille == "normale"){
            echo $tarif->taille . ":" . $tarif->tarif . "<br>";
        }
    }
}
 echo "------4. liste des produits associés à 4 recettes--------<br>";
 $produitRecette=$collection->find(['recettes'=>['$size'=>4]]);
 foreach ($produitRecette as $produit){
    echo $produit->numero . "<br>";
    echo $produit->libelle . "<br>";
    echo $produit->description . "<br>";
    echo $produit->categorie . "<br>";
 }
 echo "-------- afficher le produit n°6, compléter en listant les recettes associées-------<br>";

$produit6R=$collection->findOne(['numero'=>6]);
echo $produit6R->numero . "<br>";
echo $produit6R->libelle . "<br>";
echo $produit6R->description . "<br>";
echo $produit6R->categorie . "<br>";
foreach ($produit6R->tarifs as $tarif){
    echo $tarif->taille . ":" . $tarif->tarif . "<br>";
}
;
foreach ($produit6R->recettes as $recette){
    echo $recette . "<br>";
}

echo "---------------Fonction------- <br>";
function AfficheProduit($numeroP, $tailleP,$collection){
$produitP = $collection->findOne(
        ['numero' => $numeroP],
        ['projection' => ['numero' => 1, 'libelle' => 1, 'categorie' => 1, 'tarifs' => ['$elemMatch' => ['taille' => $tailleP]]]]
    );    return [
        'numero'=>$produitP->numero,
        'libelle'=>$produitP->libelle,
        'categorie'=>$produitP->categorie,
        'taille'=>$produitP->tarifs[0]->taille,
        'tarif'=>$produitP->tarifs[0]->tarif,
    ];
} 
$resultat = AfficheProduit(6, 'grande',$collection);

header('Content-Type: application/json');
echo json_encode($resultat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );