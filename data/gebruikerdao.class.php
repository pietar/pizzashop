<?php

require_once("data/dbconfig.class.php");
require_once("data/postcodeDAO.class.php");
require_once("entities/gebruiker.class.php");
require_once("entities/postcode.class.php");
require_once("exceptions/gebrexception.class.php");

class GebruikerDAO {

    public static function getAll() {
        $lijst = array();
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, usernm, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id";
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
            $gebruiker = Gebruiker::create($rij["gebr_id"], $rij["naam"], $rij["usernm"], $rij["pasw"],
                                           $rij["level_auth"], $rij["adres"], $rij["email"], $rij["telefoon"],
                                           $rij["actief"], $rij["dt_aangemaakt"], $rij["postcode_id"], $rij["korting"],
                                           $rij["btwnr"], $rij["opm_extra"], $postcode);
            $lijst [] = $gebruiker;
        }
        return $lijst;
    }

    public static function getById($id) {
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, usernm, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id
                   and gebruikers_pz.id = ". $id;
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
        $gebruiker = Gebruiker::create($rij["gebr_id"], $rij["naam"], $rij["usernm"], $rij["pasw"],
                                       $rij["level_auth"], $rij["adres"], $rij["email"], $rij["telefoon"],
                                       $rij["actief"], $rij["dt_aangemaakt"], $rij["postcode_id"], $rij["korting"],
                                       $rij["btwnr"], $rij["opm_extra"], $postcode);
        return $gebruiker;
    }

    public static function getByNaam($naam) {
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, usernm, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id
                   and gebruikers_pz.naam = " . "'" . $naam . "'";
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
           $gebruiker = Gebruiker::create($rij["gebr_id"], $rij["naam"], $rij["usernm"], $rij["pasw"],
                                          $rij["level_auth"], $rij["adres"], $rij["email"], $rij["telefoon"],
                                          $rij["actief"], $rij["dt_aangemaakt"], $rij["postcode_id"], $rij["korting"],
                                          $rij["btwnr"], $rij["opm_extra"], $postcode);
        } else {
            $gebruiker = null;
        }
        return $gebruiker;
    }

    public static function getByUsernm($usernm) {
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, usernm, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id
                   and gebruikers_pz.usernm = " . "'" . $usernm . "'";
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $postcode  = Postcode::create($rij["postcode_id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
           $gebruiker = Gebruiker::create($rij["gebr_id"], $rij["naam"], $rij["usernm"], $rij["pasw"],
                                          $rij["level_auth"], $rij["adres"], $rij["email"], $rij["telefoon"],
                                          $rij["actief"], $rij["dt_aangemaakt"], $rij["postcode_id"], $rij["korting"],
                                          $rij["btwnr"], $rij["opm_extra"], $postcode);
        } else {
            $gebruiker = null;
        }
        return $gebruiker;
    }
    
    
    //Bestaat_Nieuwe_Gebr_al ? 
    public static function Bestaat_Nieuwe_Gebr_al($usernm) {
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id
                   and gebruikers_pz.usernm = " . "'" . $usernm . "'";
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $gebruiker_bestaat_al = TRUE;
           throw new GebrBestaatException();
        } else {
           $gebruiker_bestaat_al = FALSE;
        }
        return $gebruiker_bestaat_al;
    }

    public static function Bestaat_Gebr_Pasw($usernm, $pasw) {
        $core = DBConfig::getInstance();
        $sql = "select gebruikers_pz.id as gebr_id, naam, pasw, 
                       level_auth, adres, email, telefoon, actief, 
                       dt_aangemaakt, postcode_id, korting, opm_extra, btwnr,
                       postcodes.gemeente as gemeente, postnr, kostprijs, thuis_lev_ok
                  from gebruikers_pz,  postcodes 
                 where postcode_id =  postcodes.id
                   and gebruikers_pz.usernm = " . "'" . $usernm . "'" . 
                   " and gebruikers_pz.pasw = " . "'" . $pasw . "'" ;
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $gebr_pasw_bestaat_niet = FALSE;
        } else {
           $gebr_pasw_bestaat_niet = TRUE;
           throw new GebrPaswBestaatNietException();
        }
        return $gebr_pasw_bestaat_niet;
    }

    public static function Get_Gebr_Actief($usernm) {
        $core = DBConfig::getInstance();
        $sql = "select actief
                  from gebruikers_pz
                 where gebruikers_pz.usernm = " . "'" . $usernm . "'" ;
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $dbh = null;
        } else {
           $gebr_pasw_bestaat_niet = TRUE;
           throw new GebrPaswBestaatNietException();
        }
        return $rij["actief"];
    }

    public static function Get_Gebr_Level($usernm, $pasw) {
        $core = DBConfig::getInstance();
        $sql = "select level_auth
                  from gebruikers_pz
                 where gebruikers_pz.usernm = " . "'" . $usernm . "'" . 
                   " and gebruikers_pz.pasw = " . "'" . $pasw . "'" ;
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $dbh = null;
        } else {
           $gebr_pasw_bestaat_niet = TRUE;
           throw new GebrPaswBestaatNietException();
        }
        return $rij["level_auth"];
    }

    public static function Get_Gebr_Level_User ($usernm) {
        $core = DBConfig::getInstance();
        $sql = "select level_auth
                  from gebruikers_pz
                 where gebruikers_pz.usernm = " . "'" . $usernm . "' limit 1"; 
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $dbh = null;
        } else {
           $gebr_bestaat_niet = TRUE;
           throw new GebrBestaatNietException();
        }
        return $rij["level_auth"];
    }
    
    
    
    public static function Create($naam, $usernm, $pasw, $level_auth, $adres, 
                                  $email, $telefoon, $actief, $dt_aangemaakt, 
                                  $postcode_id, $korting, $opm_extra, $btwnr) {
        $bestaandeGebr = self::getByNaam($naam);
        if (isset ($bestaandeGebr)) throw new GebrBestaatException();
        $core = DBConfig::getInstance();
        $sql = "insert into gebruikers_pz (naam, usernm, pasw, level_auth, adres, email, telefoon, 
                actief, dt_aangemaakt, postcode_id, korting, opm_extra, btwnr) 
                values ('" . $naam . "', " . "'" . $usernm 
                        . "', " . "'" . $pasw . "', "
                        . "'" . $level_auth .  "', " . 
                        "'" . $adres . "', " . "'" . $email . "', " . "'" . $telefoon . 
                        "', " . "'" . $actief .  "', " .
                        "'" . $dt_aangemaakt . "', " . "'" . $postcode_id . "', " . "'" . $korting . 
                        "', " . "'" . $opm_extra . "', " . "'" . $btwnr . "'".
                       ")";
        $core->dbh->exec($sql);
        $Gebr_Id = $core->dbh->lastInsertId();
        $postcode = PostcodeDAO::getById($postcode_id); 
        $gebruiker = Gebruiker::create($Gebr_Id, $naam, $usernm, $pasw, $level_auth, $adres, 
                                  $email, $telefoon, $actief, $dt_aangemaakt, 
                                  $postcode_id, $korting, $opm_extra, $btwnr, $postcode); 
        return $gebruiker; 
    }
    
    public static function Delete($id) {
        $core = DBConfig::getInstance();
        $sql = "delete from gebruikers_pz where id = ". $id;
        $core->dbh->exec($sql);
    }

    public static function update($gebruiker) {
        $sql = "update gebruikers_pz set naam='" . $gebruiker->getNaam() .
                "', level_auth=" . $gebruiker->getLevel_auth() .
                ", adres=" . "'" . $gebruiker->getAdres() .
                "', email=" .  "'" . $gebruiker->getEmail() .
                "', telefoon="  . "'" . $gebruiker->getTelefoon() .
                "', actief=" . $gebruiker->getActief() .
                ", korting=" . $gebruiker->getKorting() .
                ", opm_extra=" .  "'" . $gebruiker->getOpm_extra() .
                "', postcode_id=" . $gebruiker->getPostcode_id() .
                " where id = " . $gebruiker->getId();
        //
        // Errorchecking before the update
        //
        if ($gebruiker->getLevel_auth() <0 or $gebruiker->getLevel_auth() >1)
          throw new GebrLevel_Auth_WrongException();
        if (!is_numeric($gebruiker->getLevel_auth()) )
          throw new GebrLevel_Auth_WrongException();
        //
        if ($gebruiker->getActief() <0 or $gebruiker->getActief() >1)
          throw new GebrActief_WrongException();
        if (!is_numeric($gebruiker->getActief()) )
          throw new GebrActief_WrongException();
        //
        if ($gebruiker->getKorting() <-50 or $gebruiker->getKorting() >100)
          throw new GebrKorting_WrongException();
        if (!is_numeric($gebruiker->getKorting()) )
          throw new GebrKorting_WrongException();
        //
        $core = DBConfig::getInstance();
        $core->dbh->exec($sql);
    }

}

?>