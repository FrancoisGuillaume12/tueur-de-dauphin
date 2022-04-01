
<?php

include_once ("./crud.php");

$db = new Crud();

//print_r($db->getficheByid(22,3));

//print_r($db->getAnnuaire(2));

//print_r($db->getUserByName('Alex Goncalves'));

//print_r($db->getAnnuaireByRegion('Alex Goncalves','Mayotte'));

//$db->addSpeciliter('c++')

//print_r($db->getSpeciliterByName('css'))

//print_r($db->getAnnuaireBySpec('Astrid Breton','css'))

$db->addNewAnnuaire('Alex Goncalves','igor', 'francois', 'Vosges')














?>