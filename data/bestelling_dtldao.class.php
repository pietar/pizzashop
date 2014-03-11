<?php

require_once("data/dbconfig.class.php");
require_once("entities/bestelling_dtl.class.php");
require_once("exceptions/bestellingenexception.class.php");

class Bestelling_dtlDAO {

    public static function Create($best_id, $product, $aantal) {
        $core = DBConfig::getInstance();  
        //
        //  Aantal moet ok zijn
        //
        if ($aantal <0 or $aantal > 1000)
           throw new Bestelling_Aantal_WrongException;
        //
        $sql = "insert into bestellingen_dtl (best_id, product_id, aantal, prijs, korting) 
                values (" . $best_id . ", " .  $product->GetId()
                        . ", " . $aantal . ", "
                        . $product->GetPrijs() .  ", " 
                        . $product->GetKorting() . ")";
        $core->dbh->exec($sql);
        $Best_Dtl_Id = $core->dbh->lastInsertId();
        $bestel_dtl_extra = array();
        $best_dtl = Bestelling_dtl::create($Best_Dtl_Id, $best_id, $product->GetId(), $aantal, $product->GetPrijs(), $product->GetKorting(), 
                                           $product, $bestel_dtl_extra);
        return $best_dtl; 
    }
    public static function getDtlAll($id) {
        $lijst       = array();
        $core = DBConfig::getInstance();  
        $sql = "select dtl.id as dtl_id, best_id, product_id, 
                       aantal, dtl.prijs as dtl_prijs, dtl.korting as dtl_korting, 
                       pr.naam, pr.beschrijving, pr.prijs, pr.korting, pr.categorie, pr.status
                  from bestellingen_dtl dtl, 
                       producten pr
                 where best_id = " . $id .
                  " and product_id = pr.id";
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            //
            //
            // extra select om de extra's ook nog op te vullen
            //
            $sql_extra = "select dtl.id as dtl_id, best_id, ext.product_id as ext_product_id, 
                                 ext.id as ext_id, ext.prijs as ext_prijs, ext.korting as ext_korting, 
                                 pr.naam, pr.beschrijving, pr.prijs, pr.korting, 
                                 pr.categorie, pr.status
                            from bestellingen_dtl dtl, 
                                 bestellingen_dtl_extra ext,
                                 producten pr
                           where dtl.id = " . $rij["dtl_id"] .
                           " and ext.product_id = pr.id 
                             and best_dtl_id = dtl.id";
            //$resultSet_ext = $dbh->query($sql_extra);
            $resultSet_ext = $core->dbh->query($sql_extra);
            //
            $lijst_extra = array();
            foreach ($resultSet_ext as $rij_ext) 
            {
                //
                //  loop over alle extra's op de bestellijnen
                // hiervan objecten maken en deze in een array toevoegen
                //  
                $product_extra = Product::create($rij_ext["ext_product_id"], $rij_ext["naam"]    ,$rij_ext["beschrijving"], 
                                                 $rij_ext["prijs"]         , $rij_ext["korting"] ,$rij_ext["categorie"],
                                                 $rij_ext["status"]);
                $bestel_dtl_extra = Bestelling_dtl_extra::create($rij_ext["ext_id"], 
                                                                 $rij["dtl_id"], 
                                                                 $rij_ext["ext_product_id"], 
                                                                 $rij_ext["ext_prijs"], 
                                                                 $rij_ext["ext_korting"], 
                                                                 $product_extra);
                $lijst_extra[] = $bestel_dtl_extra;
            }         
            $product    = Product::create($rij["product_id"], $rij["naam"]   , $rij["beschrijving"], 
                                          $rij["prijs"]     , $rij["korting"], $rij["categorie"],
                                          $rij["status"]);
            $bestel_dtl = Bestelling_dtl::create($rij["dtl_id"], $rij["best_id"]  , $rij["product_id"], 
                                                 $rij["aantal"], $rij["dtl_prijs"], $rij["dtl_korting"], 
                                                 $product, $lijst_extra);
            $lijst []   = $bestel_dtl;
        }
        return $lijst;
    }    
}

?>