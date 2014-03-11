<?php

require_once("data/gebruikerdao.class.php");
require_once("data/bestelling_hdrdao.class.php");

class Bestelling_hdrService {
    
    public static function create($gebr, $thuis_lev, $uur_lev) 
    {
        $best_hdr = Bestelling_hdrDAO::Create($gebr, $thuis_lev, $uur_lev);
        return $best_hdr;
    }
    
    public static function GetById($id) {
        $best_hdr = Bestelling_hdrDAO::getById($id);
        return $best_hdr;
    }    
    
    public static function update($best_id, $thuis_lev, $uur_lev, $status)
     {
        $best_hdr = Bestelling_hdrDAO::getById($best_id);
        $best_hdr->setThuis_lev($thuis_lev);
        $best_hdr->setUur_lev($uur_lev);
        $best_hdr->setStatus($status);
        Bestelling_hdrDAO::update($best_hdr);
    }    
   
    public static function toonBestellingen_date ($date1, $date2)
    {
        $lijst = Bestelling_hdrDAO::getDtlAll_date($date1, $date2);
        return $lijst;
    }
    public static function toonLaatsteBestelling_gebr ($id)
    {
        $lijst = Bestelling_hdrDAO::getDtlLast_gebr($id);
        return $lijst;
    }
    public static function toonBestelling_gebr_products ($id, $uur_lev)
    {
        $lijst = Bestelling_hdrDAO::getDtl_gebr_products($id, $uur_lev);
        return $lijst;
    }
    public static function delete($best_id)
     {
        Bestelling_hdrDAO::delete($best_id);
    }    
    
}

