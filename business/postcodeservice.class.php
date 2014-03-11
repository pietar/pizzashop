<?php

require_once("data/postcodedao.class.php");

class PostcodeService {

    public static function toonPostcodes() {
        $lijst_postcodes = PostcodeDAO::getAll();
        return $lijst_postcodes;
    }
    public static function getPostcodes_in_array() {
        $lijst_postcodes = PostcodeDAO::getAll_in_array();
        return $lijst_postcodes;
    }
    public static function haalPostcodeOp($id) {
        $pc = PostcodeDAO::getById($id);
        return $pc;
    }
    public static function Bestaat_Postnr_Gemeente_al($postnr, $gemeente) 
    {
        $pc = PostcodeDAO::Bestaat_Postnr_Gemeente_al($postnr, $gemeente); 
    }
    public static function verwijderPostcode($id) {
        PostcodeDAO::Delete($id);
    }
    public static function updatePostcode($id, $postnr, $gemeente, $kostprijs, 
                                         $thuis_lev_ok) {
        $pc = PostcodeDAO::getById($id);
        $pc->setPostnr($postnr);
        $pc->setGemeente($gemeente);
        $pc->setKostprijs($kostprijs);
        $pc->setThuis_lev_ok($thuis_lev_ok);
        PostcodeDAO::update($pc);
    }   
    public static function voegPostcodeToe($postnr, $gemeente, $kostprijs, $thuis_lev_ok) 
    {
        $postcode = PostcodeDAO::Create($postnr, $gemeente, $kostprijs, $thuis_lev_ok);
    }
    
}

