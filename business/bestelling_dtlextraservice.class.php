<?php

require_once("data/gebruikerdao.class.php");
require_once("data/bestelling_dtl_extradao.class.php");

class Bestelling_dtlextraService {
    
    public static function create($best_dtl_id, $product) 
    {
        $best_dtl_extra = Bestelling_dtl_extraDAO::Create($best_dtl_id, $product);
        return $best_dtl_extra;
    }
    
}

