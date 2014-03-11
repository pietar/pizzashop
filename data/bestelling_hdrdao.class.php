<?php

require_once("data/dbconfig.class.php");
require_once("data/postcodeDAO.class.php");
require_once("data/gebruikerDAO.class.php");
require_once("entities/gebruiker.class.php");
require_once("entities/postcode.class.php");
require_once("entities/bestelling_hdr.class.php");
require_once("exceptions/bestellingenexception.class.php");


class Bestelling_hdrDAO {

    public static function Create($gebr, $thuis_lev, $uur_lev) {
        $core = DBConfig::getInstance();        
        // get system date and time to store in table 
        $datum_best  = date('y-m-d h:i:s'); 
        // date levering, omvormen voor db en omvormen voor controle geldigheid
        $uur_lev_mysql = date("Y-m-d H:i", strtotime($uur_lev));
        //
        $maand  = substr($uur_lev,3,2);
        $dag    = substr($uur_lev,0,2);
        $jaar   = substr($uur_lev,6,4 );
        $uur    = substr($uur_lev,11,2);
        $minuut = substr($uur_lev,14,2);
        //
        $date_ok = checkdate($maand, $dag, $jaar);
        //
        if (!$date_ok or ($uur < 0 or $uur > 23) or ($minuut < 0 or $minuut > 59))
           {throw new Bestelling_Uurlev_WrongException;}
        //
        $sql = "insert into bestellingen_hdr (gebr_id, dt_best, thuis_lev, uur_lev, status, extra_kost_lev) 
                values (" . $gebr->GetId() . ", " . "'" . $datum_best
                        . "', " . $thuis_lev . ", "
                        . "'" . $uur_lev_mysql .  "',0, " 
                        . $gebr->GetPostcode()->GetKostprijs() . ")";
        $core->dbh->exec($sql);
        $Best_Id = $core->dbh->lastInsertId();
        $best_hdr = Bestelling_hdr::create($Best_Id   , $gebr->GetId(), 
                                           $datum_best, $thuis_lev, 
                                           $uur_lev   , 0, 
                                           $gebr->GetPostcode()->Getkostprijs(), 
                                           null);
        return $best_hdr; 
    }

    public static function getById($id) {
        $core = DBConfig::getInstance();        
        $sql = "select id, dt_best, thuis_lev, uur_lev, extra_kost_lev, gebr_id, status
                  from bestellingen_hdr 
                 where id = ". $id;
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        $best_hdr  = Bestelling_hdr::create($rij["id"]       , $rij["gebr_id"], $rij["dt_best"], 
                                            $rij["thuis_lev"], $rij["uur_lev"], $rij["status"],
                                            $rij["extra_kost_lev"], null);
        return $best_hdr;
    }

    public static function update($best_hdr) {

        $uur_lev = $best_hdr->getUur_lev();
        //
        $uur_lev_mysql = date("Y-m-d H:i", strtotime($uur_lev));
        //
        $maand  = substr($uur_lev,3,2);
        $dag    = substr($uur_lev,0,2);
        $jaar   = substr($uur_lev,6,4 );
        $uur    = substr($uur_lev,11,2);
        $minuut = substr($uur_lev,14,2);
        //
        $date_ok = checkdate($maand, $dag, $jaar);
        //
        if (!$date_ok or ($uur < 0 or $uur > 23) or ($minuut < 0 or $minuut > 59))
           {throw new Bestelling_Uurlev_WrongException;}
        //
        $sql = "update bestellingen_hdr set thuis_lev=" . $best_hdr->getThuis_lev() .
                ", uur_lev='" . $uur_lev_mysql . "'" . 
                ", status=" . $best_hdr->getStatus() . 
                " where id = " . $best_hdr->getId();
        $core = DBConfig::getInstance();        
        $core->dbh->exec($sql);
        $dbh = null;
    }

    public static function getDtlAll_date($date1, $date2) {
        $core = DBConfig::getInstance();        
        //
        $uur_lev_mysql1 = substr($date1, 6, 4) . "-" . substr($date1, 3, 2) . "-" . substr($date1, 0, 2) . " " . substr($date1, 11, 10);
        $uur_lev_mysql2 = substr($date2, 6, 4) . "-" . substr($date2, 3, 2) . "-" . substr($date2, 0, 2) . " " . substr($date2, 11, 10);
        //
        $maand  = substr($date1,3,2);
        $dag    = substr($date1,0,2);
        $jaar   = substr($date1,6,4 );
        $uur    = substr($date1,11,2);
        $minuut = substr($date1,14,2);
        //
        $date_ok = checkdate($maand, $dag, $jaar);
        //
        if (!$date_ok or ($uur < 0 or $uur > 23) or ($minuut < 0 or $minuut > 59))
           {throw new Bestelling_Uurlev_WrongException;}
        //        
        $maand  = substr($date2,3,2);
        $dag    = substr($date2,0,2);
        $jaar   = substr($date2,6,4 );
        $uur    = substr($date2,11,2);
        $minuut = substr($date2,14,2);
        //
        $date_ok = checkdate($maand, $dag, $jaar);
        //
        if (!$date_ok or ($uur < 0 or $uur > 23) or ($minuut < 0 or $minuut > 59))
           {throw new Bestelling_Uurlev_WrongException;}
        //        
        $sql = "select hdr.gebr_id, hdr.thuis_lev, hdr.status, hdr.extra_kost_lev, 
                       hdr.uur_lev, hdr.id as hdr_id, hdr.dt_best, hdr.gebr_id,
                       gb.postcode_id, gb.naam, gb.adres, gb.telefoon, 
                       pc.gemeente, pc.postnr, pc.kostprijs
                  from bestellingen_hdr hdr, 
                       gebruikers_pz gb, 
                       postcodes pc
                 where hdr.gebr_id   = gb.id
                   and gb.postcode_id = pc.id
                   and hdr.status    = 1
                   and hdr.thuis_lev = 1
                   and hdr.uur_lev between '" . $uur_lev_mysql1 .
                "' and '" . $uur_lev_mysql2 .
                "' order by hdr.uur_lev, gb.postcode_id, pc.gemeente";
        //
        $resultSet = $core->dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij_hdr) {            
            $postcode   = Postcode::create($rij_hdr["postcode_id"], $rij_hdr["postnr"], 
                                           $rij_hdr["gemeente"]   , $rij_hdr["kostprijs"], null);
            $gebr       = Gebruiker::create($rij_hdr["gebr_id"], $rij_hdr["naam"], 
                                            null, null, null, 
                                            $rij_hdr["adres"], null, $rij_hdr["telefoon"], null, 
                                            null, $rij_hdr["postcode_id"], null, 
                                            null, null, $postcode);
            $bestel_hdr = Bestelling_hdr::create($rij_hdr["hdr_id"]   , $rij_hdr["gebr_id"], $rij_hdr["dt_best"], 
                                                 $rij_hdr["thuis_lev"], $rij_hdr["uur_lev"], $rij_hdr["status"], 
                                                 $rij_hdr["extra_kost_lev"], $gebr);
            $lijst[] = $bestel_hdr;
            //            
            $sql_dtl = "select dtl.id as dtl_id, best_id, product_id, 
                       aantal, dtl.prijs as dtl_prijs, dtl.korting as dtl_korting, 
                       pr.naam, pr.beschrijving, pr.prijs, pr.korting, pr.categorie, pr.status
                  from bestellingen_dtl dtl, 
                       producten pr
                 where product_id   = pr.id
                   and dtl.best_id  = " . $rij_hdr["hdr_id"];
            $resultSet = $core->dbh->query($sql_dtl);
            foreach ($resultSet as $rij_dtl) {
                //
                $product = Product::create($rij_dtl["product_id"], $rij_dtl["naam"], $rij_dtl["beschrijving"], $rij_dtl["prijs"], $rij_dtl["korting"], $rij_dtl["categorie"], $rij_dtl["status"]);
                $bestel_dtl = Bestelling_dtl::create($rij_dtl["dtl_id"], $rij_dtl["best_id"], $rij_dtl["product_id"], $rij_dtl["aantal"], $rij_dtl["dtl_prijs"], $rij_dtl["dtl_korting"], $product, NULL);
                $lijst[]    = $bestel_dtl;
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
                           where dtl.id = " . $rij_dtl["dtl_id"] .
                        " and ext.product_id = pr.id 
                             and best_dtl_id = dtl.id";
                $resultSet_ext = $core->dbh->query($sql_extra);
                //
                $lijst_extra = array();
                foreach ($resultSet_ext as $rij_ext) {
                    //
                    //  loop over alle extra's op de bestellijnen
                    // hiervan objecten maken en deze in een array toevoegen
                    //  
                    $product_extra = Product::create($rij_ext["ext_product_id"], $rij_ext["naam"], $rij_ext["beschrijving"], $rij_ext["prijs"], $rij_ext["korting"], $rij_ext["categorie"], $rij_ext["status"]);
                    $bestel_dtl_extra = Bestelling_dtl_extra::create($rij_ext["ext_id"], $rij_dtl["dtl_id"], $rij_ext["ext_product_id"], $rij_ext["ext_prijs"], $rij_ext["ext_korting"], $product_extra);
                    $lijst[]= $bestel_dtl_extra;
                }
                //
                //  einde loop extra's
                //
            }
            // 
            // einde loop details    
            //
        }
        // 
        // einde loop header
        // 
        return $lijst;
    }
    
  public static function getDtlLast_gebr($id) {
        $core = DBConfig::getInstance();        
        //
        $sql = "select hdr.gebr_id, hdr.thuis_lev, hdr.status, hdr.extra_kost_lev, 
                       hdr.uur_lev, hdr.id as hdr_id, hdr.dt_best, hdr.gebr_id,
                       gb.postcode_id, gb.naam, gb.adres, gb.telefoon, 
                       pc.gemeente, pc.postnr, pc.kostprijs
                  from bestellingen_hdr hdr, 
                       gebruikers_pz gb, 
                       postcodes pc
                 where hdr.gebr_id   = gb.id
                   and gb.postcode_id = pc.id
                   and hdr.status    >0 
                   and hdr.gebr_id = ".$id . 
                " order by hdr.uur_lev, gb.postcode_id, pc.gemeente";
        //
        $resultSet = $core->dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij_hdr) {            
            $postcode   = Postcode::create($rij_hdr["postcode_id"], $rij_hdr["postnr"], 
                                           $rij_hdr["gemeente"]   , $rij_hdr["kostprijs"], null);
            $gebr       = Gebruiker::create($rij_hdr["gebr_id"], $rij_hdr["naam"], 
                                            null, null, null, 
                                            $rij_hdr["adres"], null, $rij_hdr["telefoon"], null, 
                                            null, $rij_hdr["postcode_id"], null, 
                                            null, null, $postcode);
            $bestel_hdr = Bestelling_hdr::create($rij_hdr["hdr_id"]   , $rij_hdr["gebr_id"], $rij_hdr["dt_best"], 
                                                 $rij_hdr["thuis_lev"], $rij_hdr["uur_lev"], $rij_hdr["status"], 
                                                 $rij_hdr["extra_kost_lev"], $gebr);
            $lijst[] = $bestel_hdr;
            //            
            $sql_dtl = "select dtl.id as dtl_id, best_id, product_id, 
                       aantal, dtl.prijs as dtl_prijs, dtl.korting as dtl_korting, 
                       pr.naam, pr.beschrijving, pr.prijs, pr.korting, pr.categorie, pr.status
                  from bestellingen_dtl dtl, 
                       producten pr
                 where product_id   = pr.id
                   and dtl.best_id  = " . $rij_hdr["hdr_id"];
            $resultSet = $core->dbh->query($sql_dtl);
            foreach ($resultSet as $rij_dtl) {
                //
                $product = Product::create($rij_dtl["product_id"], $rij_dtl["naam"], $rij_dtl["beschrijving"], $rij_dtl["prijs"], $rij_dtl["korting"], $rij_dtl["categorie"], $rij_dtl["status"]);
                $bestel_dtl = Bestelling_dtl::create($rij_dtl["dtl_id"], $rij_dtl["best_id"], $rij_dtl["product_id"], $rij_dtl["aantal"], $rij_dtl["dtl_prijs"], $rij_dtl["dtl_korting"], $product, NULL);
                $lijst[]    = $bestel_dtl;
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
                           where dtl.id = " . $rij_dtl["dtl_id"] .
                        " and ext.product_id = pr.id 
                             and best_dtl_id = dtl.id";
                $resultSet_ext = $core->dbh->query($sql_extra);
                //
                $lijst_extra = array();
                foreach ($resultSet_ext as $rij_ext) {
                    //
                    //  loop over alle extra's op de bestellijnen
                    // hiervan objecten maken en deze in een array toevoegen
                    //  
                    $product_extra = Product::create($rij_ext["ext_product_id"], $rij_ext["naam"], $rij_ext["beschrijving"], $rij_ext["prijs"], $rij_ext["korting"], $rij_ext["categorie"], $rij_ext["status"]);
                    $bestel_dtl_extra = Bestelling_dtl_extra::create($rij_ext["ext_id"], $rij_dtl["dtl_id"], $rij_ext["ext_product_id"], $rij_ext["ext_prijs"], $rij_ext["ext_korting"], $product_extra);
                    $lijst[]= $bestel_dtl_extra;
                }
                //
                //  einde loop extra's
                //
            }
            // 
            // einde loop details    
            //
        }
        // 
        // einde loop header
        // 
        return $lijst;
    }    
    
    public static function getDtl_gebr_products($id, $uur_lev) {
        $core = DBConfig::getInstance();        
        //
        $uur_lev_mysql = date("Y-m-d H:i", strtotime($uur_lev));
        //
        $maand  = substr($uur_lev,3,2);
        $dag    = substr($uur_lev,0,2);
        $jaar   = substr($uur_lev,6,4 );
        $uur    = substr($uur_lev,11,2);
        $minuut = substr($uur_lev,14,2);
        //
        $sql = "select sum(aantal) as totaal, product_id, pr.naam
                  from bestellingen_hdr hdr, 
                       bestellingen_dtl dtl,
                       producten pr
                 where hdr.status    >0 
                   and dtl.best_id = hdr.id
                   and dtl.product_id = pr.id
                   and hdr.gebr_id = ".$id . 
                 " and hdr.uur_lev > '" . $uur_lev_mysql . "'" . 
                " group by product_id, pr.naam
                  order by pr.naam, hdr.uur_lev";
        //
        //print ("maand = ".$maand . "dag = ".$dag . "jaar = ".$jaar);
        //
        //echo ($sql);
        $date_ok = checkdate($maand, $dag, $jaar);
        //
        if (!$date_ok or ($uur < 0 or $uur > 23) or ($minuut < 0 or $minuut > 59))
           {throw new Bestelling_Uurlev_WrongException;}
        //
        $teller = 0;
        $resultSet = $core->dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij_hdr) {            
            //      
            $teller = $teller +1;
            $product = Product::create($rij_hdr["product_id"], $rij_hdr["naam"], null, null, null, null, null);
            $bestel_dtl = Bestelling_dtl::create($teller, null, $rij_hdr["product_id"], $rij_hdr["totaal"], null, null, $product, NULL);
            $lijst[] = $bestel_dtl;
        //
        }
        return $lijst;
    }        

    public static function delete($best_id) {
        // 
        // niveau extra's
        //
        $sql = "delete from bestellingen_dtl_extra
                where best_dtl_id in 
                (select id from bestellingen_dtl
                 where  best_id = " . $best_id .     
                ")";
        $core = DBConfig::getInstance();        
        $core->dbh->exec($sql);
        // 
        // niveau detaillijnen
        //
        $sql = "delete from bestellingen_dtl
                where best_id = " . $best_id;
        $core = DBConfig::getInstance();        
        $core->dbh->exec($sql);
        // 
        // niveau header
        //
        $sql = "delete from bestellingen_hdr
                where id = " . $best_id;
        $core = DBConfig::getInstance();        
        $core->dbh->exec($sql);
        //
    }

}
?>