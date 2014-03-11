<?php

require_once("data/gebruikerdao.class.php");
require_once("data/bestelling_dtldao.class.php");

class Bestelling_dtlService {
    
    public static function create($best_id, $product, $aantal) 
    {
        $best_dtl = Bestelling_dtlDAO::Create($best_id, $product, $aantal);
        return $best_dtl;
    }
    
    public static function toonBestellingen_Dtl_id($id) {
        $lijst_dtl = Bestelling_dtlDAO::getDtlAll($id);
        return $lijst_dtl;
    }    
}

