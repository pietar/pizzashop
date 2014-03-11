<?php

require_once("data/dbconfig.class.php");
require_once("entities/bedrijfsinfo.class.php");
require_once("entities/postcode.class.php");

class BedrijfsDAO {

    public static function getAll() {
        $lijst = array();
        $core = DBConfig::getInstance();        
        $sql = "select bedrijfsinfo.id as bedr_id, naam, adres, 
                       postcode_id, telefoon, gsm, email, lev_vw,
                       postcodes.gemeente as gemeente, openinguren, logo, alg_info, 
                       promotie, faq_info, 
                       postcodes.kostprijs as kostprijs, 
                       postcodes.gemeente as gemeente,
                       postcodes.thuis_lev_ok as thuis_lev_ok, postcodes.postnr
                  from bedrijfsinfo,  postcodes 
                 where postcode_id =  postcodes.id
                 limit 1";
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
            $bedrijfsinfo = Bedrijfsinfo::create($rij["bedr_id"], $rij["naam"], $rij["adres"], $rij["email"], 
                                           $rij["telefoon"], $rij["gsm"], $rij["lev_vw"], $rij["openinguren"],
                                           $rij["postcode_id"], $rij["logo"], $rij["alg_info"], $rij["promotie"],
                                           $rij["faq_info"], $postcode);
            $lijst [] = $bedrijfsinfo;
        }
        return $lijst;
    }

    public static function getById($id) {
        $core = DBConfig::getInstance();        
        $sql = "select bedrijfsinfo.id as bedr_id, naam, adres, 
                       postcode_id, telefoon, gsm, email, lev_vw,
                       postcodes.gemeente as gemeente, openinguren, logo, alg_info, 
                       promotie, faq_info, 
                       postcodes.kostprijs as kostprijs, 
                       postcodes.gemeente as gemeente,
                       postcodes.thuis_lev_ok as thuis_lev_ok, postcodes.postnr
                  from bedrijfsinfo,  postcodes 
                 where postcode_id =  postcodes.id
                   and bedrijfsinfo.id = " . $id . 
                 " limit 1";
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
        $bedrijfsinfo = Bedrijfsinfo::create($rij["bedr_id"], $rij["naam"], $rij["adres"], $rij["email"], 
                                             $rij["telefoon"], $rij["gsm"], $rij["lev_vw"], $rij["openinguren"],
                                             $rij["postcode_id"], $rij["logo"], $rij["alg_info"], $rij["promotie"],
                                             $rij["faq_info"], $postcode);
        return $bedrijfsinfo;
    }

    public static function Create($naam, $adres, $postcode_id, $telefoon, $gsm, 
                                  $email, $lev_vw, $openingsuren, $logo, 
                                  $alg_info, $promotie, $faq_info) {
        $core = DBConfig::getInstance();        
        $sql = "insert into bedrijfsinfo (naam, adres, postcode_id, telefoon, gsm, 
                                          email, lev_vw, openingsuren, logo, 
                                          alg_info, promotie, faq_info) 
                values ('" . $naam . "', " . $adres . "', " . $postcode_id .  "', " . 
                          $telefoon . "', " . $gsm . "', " . $email . $lev_vw .  "', " .
                          $openingsuren . "', " . $logo . "', " . $alg_info . 
                          "', " . $promotie . "', " . $faq_info . 
                       ")";
                
        $core->dbh->exec($sql);
        $Bedr_Id = $core->dbh->lastInsertId();
        $postcode = PostcodeDAO::getById($postcode_id); 
        $bedrijfsinfo = Bedrijfsinfo::create($Bedr_Id, $naam, $adres, $postcode_id, $telefoon, $gsm, 
                                  $email, $lev_vw, $openingsuren, $logo, 
                                  $alg_info, $promotie, $faq_info, $postcode); 
        return $bedrijfsinfo; 
    }
    
    public static function Delete($id) {
        $core = DBConfig::getInstance();        
        $sql = "delete from bedrijfsinfo where id = ". $id;
        $core->dbh->exec($sql);
    }

    public static function update($bedr_info) {
        $sql = "update bedrijfsinfo set naam='" . $bedr_info->getNaam() .
                "', adres=" . "'" . $bedr_info->getAdres() .
                "', postcode_id=" . $bedr_info->getPostcode_Id().
                ", telefoon=" . "'" . $bedr_info->getTelefoon() .
                "', gsm=" . "'" . $bedr_info->getGsm() .
                "', email=" . "'" . $bedr_info->getEmail() .
                "', lev_vw=" . "'" . $bedr_info->getLev_vw() .
                "', openinguren=" . "'" . $bedr_info->getOpeninguren() .
                "', logo=" . "'" . $bedr_info->getLogo() .
                "', alg_info=" . "'" . $bedr_info->getAlg_info() .
                "', promotie=" . "'" . $bedr_info->getPromotie() .
                "', faq_info=" . "'" . $bedr_info->getFaq_info() .
                "' where id = " . $bedr_info->getId();
        $core = DBConfig::getInstance();        
        $core->dbh->exec($sql);
    }

}

?>