<?php

require_once("data/bedrijfsinfodao.class.php");

class BedrijfsinfoService {

    public static function toonBedrijfInfo() {
        $lijst_bedrijf = BedrijfsDAO::getAll();
        return $lijst_bedrijf;
    }
    
    public static function updateBedrijf($id, $naam, $adres, $postcode_id, 
                                         $telefoon, $gsm, $email, $openinguren, 
                                         $alg_info, $promotie, $lev_vw, $faq_info) {
        
        $bd_info = BedrijfsDAO::getById($id);
        $bd_info->setNaam($naam);
        $bd_info->setAdres($adres);
        $bd_info->setPostcode_id($postcode_id);
        $bd_info->setTelefoon($telefoon);
        $bd_info->setGsm($gsm);
        $bd_info->setEmail($email);
        $bd_info->setOpeninguren($openinguren);
        $bd_info->setAlg_info($alg_info);
        $bd_info->setPromotie($promotie);
        $bd_info->setLev_vw($lev_vw);
        $bd_info->setFaq_info($faq_info);
        BedrijfsDAO::update($bd_info);
        
    }    
}

