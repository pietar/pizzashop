<?php

require_once("data/gebruikerdao.class.php");

class GebruikerinfoService {
    
    public static function toonGebruikers() {
        $lijst_gebr = GebruikerDAO::getAll();
        return $lijst_gebr;
    }

    public static function voegNieuweGebrToe($naam, $usernm, $pasw, $level_auth, $adres, 
                                             $email, $telefoon, $actief, 
                                             $dt_aangemaakt, $postcode_id, $korting, 
                                             $opm_extra, $btwnr) 
    {
        $lijst_gebr = GebruikerDAO::Create($naam, $usernm, $pasw, $level_auth, $adres, 
                                           $email, $telefoon, $actief, 
                                           $dt_aangemaakt, $postcode_id, $korting, 
                                           $opm_extra, $btwnr);
    }

    public static function Bestaat_Nieuwe_Gebr_al($usernm) 
    {
        $lijst_gebr = GebruikerDAO::Bestaat_Nieuwe_Gebr_al($usernm); 
    }

    public static function Bestaat_Gebr_Pasw($usernm, $pasw) 
    {
        $lijst_gebr = GebruikerDAO::Bestaat_Gebr_Pasw($usernm, $pasw); 
    }
    
    public static function Get_Gebr_Actief ($usernm) 
    {
        $actief = GebruikerDAO::Get_Gebr_Actief($usernm); 
        return $actief;
    }

    public static function Get_Gebr_Level ($usernm, $pasw) 
    {
        $level_auth = GebruikerDAO::Get_Gebr_Level($usernm, $pasw); 
        return $level_auth;
    }

    public static function Get_Gebr_Level_User ($usernm) 
    {
        $level_auth = GebruikerDAO::Get_Gebr_Level_User($usernm); 
        return $level_auth;
    }
    
    public static function verwijderGebr($id) {
        GebruikerDAO::Delete($id);
    }
    
    public static function haalGebrOp($id) {
        $gebr = GebruikerDAO::getById($id);
        return $gebr;
    }

    public static function haalGebrOp_Usernm($usernm) {
        $gebr = GebruikerDAO::getByUsernm($usernm);
        return $gebr;
    }

    public static function updateGebr($id, $naam, $level_auth, 
                                      $adres, $email, $telefoon, $actief,
                                      $postcode_id, $korting, $opm_extra) {
        $gebr = GebruikerDAO::getById($id);
        $gebr->setNaam($naam);
        $gebr->setLevel_auth($level_auth);
        $gebr->setAdres($adres);
        $gebr->setEmail($email);
        $gebr->setTelefoon($telefoon);
        $gebr->setActief($actief);
        $gebr->setPostcode_id($postcode_id);
        $gebr->setKorting($korting);
        $gebr->setOpm_extra($opm_extra);
        GebruikerDAO::update($gebr);
    }
    
    public static function Thuis_lev_mogelijk ($usernm) 
    {
        $gebr = GebruikerDAO::getByUsernm($usernm);
        if (isset ($gebr))
          {return ($gebr->GetPostcode()->getThuis_lev_ok() );}
        else
          {return 0;}
    }

    
    
}

