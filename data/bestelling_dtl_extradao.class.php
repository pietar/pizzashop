<?php

require_once("data/dbconfig.class.php");
require_once("entities/product.class.php");
require_once("entities/bestelling_dtl.class.php");
require_once("entities/bestelling_dtl_extra.class.php");

class Bestelling_dtl_extraDAO {

    public static function Create($best_dtl_id, $product) {
        $core = DBConfig::getInstance();        
        $sql = "insert into bestellingen_dtl_extra (best_dtl_id, product_id, prijs, korting) 
                values (" . $best_dtl_id . ", " .  $product->GetId()
                        . ", " . $product->GetPrijs() .  ", " 
                        . $product->GetKorting() . ")";
        $core->dbh->exec($sql);
        $Best_Dtl_Extra_Id = $core->dbh->lastInsertId();
        $best_dtl_extra = Bestelling_dtl_extra::create($Best_Dtl_Extra_Id     , $best_dtl_id, 
                                                       $product->GetId()      , $product->GetPrijs(), 
                                                       $product->GetKorting() , $product);
        return $best_dtl_extra; 
    }

    
}

?>