<?php

use LDAP\Result;

class Crud {
///proprietes
public $database;


///constructeur
public function  __construct()
{
    $this->database = new pdo('mysql:host=localhost;dbname=annuaire_de_maitre;charset=utf8mb4','root','');
}

    public function getUserByName($name){

        $req = "SELECT * from `user` where nom = :nom";
        $stats= $this->database->prepare($req);
        $stats->execute(['nom'=>$name]);

        $result = $stats->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getSpeciliterByName($name){
        $req = "SELECT * from `specialiter` where specialiter = :nom";
        $stats= $this->database->prepare($req);
        $stats->execute(['nom'=>$name]);

        $result = $stats->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

        



    public function getficheByid($id,$id2){

    //return une fiche complete avec tout les email, les tel, les spec
        $req = "SELECT * FROM `annuaire` WHERE  IDannuaire = :id ";
        $req2 = "SELECT `email` FROM `email` WHERE IDannuaire = :id";
        $req3 = "SELECT `numero_de_telephone` FROM `numero_de_telephone` WHERE IDannuaire = :id";
        $req4 = "SELECT  `specialiter` FROM `specialiter` WHERE IDspecialiter = :id";
        $stats = $this->database->prepare($req);
        $stats2 = $this->database->prepare($req2);
        $stats3 = $this->database->prepare($req3);
        $stats4 = $this->database->prepare($req4);
        $stats->execute(['id'=>$id]);
        $stats2->execute(['id'=>$id]);
        $stats3->execute(['id'=>$id]);
        $stats4->execute(['id'=>$id2]);
        $data = $stats->fetchAll(PDO::FETCH_ASSOC);
        $data2 = $stats2->fetchAll(PDO::FETCH_ASSOC);
        $data3 = $stats3->fetchAll(PDO::FETCH_ASSOC);
        $data4 = $stats4->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        array_push($result,[$data,$data2,$data3,$data4]);
        return $result;

        

}
    public function getAnnuaire($id){

        //return tout les fiches d'un annauire d'user (les fiches non detaillee)
        $req = "SELECT * FROM `annuaire` WHERE  IDuser = :id ";
        $stats = $this->database->prepare($req);
        $stats->execute(['id'=>$id]);
        $result = $stats->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    


//une method pour creer une fiche à partir d'un user 
        //moi en tant user je souhaite creer une nouvelle fiche dans mon annauire

    public function addNewAnnuaire($name,$lastName,$firstName,$adress){
        $user = $this->getUserByName($name);
        
        $req =  "INSERT INTO `annuaire` (`IDuser`, `nom`, `prenom`, `adresse`) VALUES (:IDuser,:nom,:prenom, :adresse)";
        $stat = $this->database->prepare($req);
        $stat->execute(['IDuser'=>$user['IDuser'],':nom' =>$lastName ,':prenom'=>$firstName, ':adresse'=>$adress]);
    }


//method pour recuperer tout les fiche non detaille par rapport a une spec
        //moi en tant user je souhaite savoir les fiches qui on pour qualites html par exemple 

    public function getAnnuaireBySpec($name,$nameSpec){
        $spec = $this->getSpeciliterByName($nameSpec);
        $user = $this->getUserByName($name);
        $annuaire = $this->getAnnuaire($user['IDuser']);
        $result = [];
        foreach($annuaire as $value){
            $req = "SELECT * FROM `annuaire/specialiter` where IDspecialiter = :IDspecialiter and IDannuaire = :IDannuaire ";
            $stats = $this->database->prepare($req);
            $stats->execute(['IDspecialiter'=>$spec['IDspecialiter'],'IDannuaire'=>$value['IDannuaire']]);
            $data = $stats->fetch(PDO::FETCH_ASSOC);
            if (empty($data) ){
                
            }
            else{
                array_push($result,$data);
            }
            
            
        }
        $dauphinKill = [];
        foreach ($result as $valeur) {
            $dauphin = $this->getficheByid($valeur['IDannuaire'],$spec['IDspecialiter']);
             array_push($dauphinKill,$dauphin);
             
        }
        
        return $dauphinKill;
        
    }

//method pour recuperer tout les fiche non detaille par rapport a une region
    //moi en tant user je souhaite savoir les fiches regions paca par exemple 
    public function getAnnuaireByRegion($name,$region){
        $user = $this->getUserByName($name);
        
        $req = "SELECT * FROM `annuaire` WHERE IDuser = :id AND adresse = :region ";
        $stats = $this->database->prepare($req);
        $stats->execute([':id'=>$user['IDuser'],':region'=>$region]);
        $result = $stats->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//method pour inserer une nouvelle competence 

    public function addSpeciliter($specialiter){
        
        $req = " INSERT INTO `specialiter`(`specialiter`) VALUES (:specialiter) ";
        $stats=$this->database->prepare($req);
        $stats->execute(['specialiter'=>$specialiter]);

    }
}
?>