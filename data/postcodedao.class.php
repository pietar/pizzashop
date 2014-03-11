<?php

require_once("data/dbconfig.class.php");
require_once("entities/postcode.class.php");
require_once("exceptions/gemeenteexception.class.php");

class PostcodeDAO {
    public static function getAll() {
        $lijst = array();
        $core = DBConfig::getInstance();        
        $sql = "select id, postnr, gemeente, kostprijs, thuis_lev_ok
                  from postcodes order by postnr, gemeente"; 
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $postcode  = Postcode::create($rij["id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
            $lijst [] = $postcode;
        }
        return $lijst;
    }

    public static function getAll_in_array() {
        $lijst = array();
        $core = DBConfig::getInstance();        
        $sql = "select id, postnr, gemeente, kostprijs, thuis_lev_ok
                  from postcodes"; 
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $lijst [] = array ('gemeente' => $rij["gemeente"], 'id'=> $rij ["id"], 'postnr' => $rij ["postnr"]);
        }
        sort ($lijst);
        return $lijst;
    }    
    public static function getById($id) {
        $core = DBConfig::getInstance();        
        $sql = "select id, postnr, gemeente, kostprijs, thuis_lev_ok
                  from postcodes 
                 where id = ". $id;
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        $postcode  = Postcode::create($rij["id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
        return $postcode;
    }

    public static function getByGemeente($gemeente, $postnr) {
        $core = DBConfig::getInstance();        
        $sql = "select id, postnr, gemeente, kostprijs, thuis_lev_ok
                  from postcodes
                 where gemeente = " . "'" . $gemeente . "'" . 
                  "and  postnr = ". $postnr;
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $postcode  = Postcode::create($rij["id"], $rij["postnr"], $rij["gemeente"], $rij["kostprijs"], $rij["thuis_lev_ok"]);
        } else {
            $postcode = null;
        }
        return $postcode;
    }
    
    public static function Create($postnr, $gemeente, $kostprijs, $thuis_lev_ok) {
        $core = DBConfig::getInstance();        
        $sql = "insert into postcodes (postnr, gemeente, kostprijs, thuis_lev_ok) 
                values ('" . $postnr . "','" . $gemeente . "', " . $kostprijs .  ", " . 
                          $thuis_lev_ok . 
                       ")";
                
        //
        // Errorchecking before the update
        //
        if ($kostprijs <0 or $kostprijs >100)
          throw new GemeenteKostprijs_WrongException();
        if (!is_numeric($kostprijs) )
          throw new GemeenteKostprijs_WrongException();
        //
        if ($thuis_lev_ok <0 or $thuis_lev_ok >1)
          throw new GemeenteThuislev_WrongException();
        if (!is_numeric($thuis_lev_ok) )
          throw new GemeenteThuislev_WrongException();
        //
        $core->dbh->exec($sql);
        $postcode_Id = $core->dbh->lastInsertId();
        $postcode = Postcode::create($postcode_Id, $postnr, $gemeente, $kostprijs, $thuis_lev_ok); 
        return $postcode; 
    }
    
    public static function Delete($id) {
        $core = DBConfig::getInstance();        
        $sql = "delete from postcodes where id = ". $id;
        $core->dbh->exec($sql);
    }

    public static function update($postcode) {
        $bestaande_pc = self::getByGemeente($postcode->getGemeente(), $postcode->getPostnr() );
        if (isset ($bestaande_pc) && $bestaande_pc->getId() != $postcode->getId() )
        {
            throw new GemeenteBestaatException();
        }
        $sql = "update postcodes set gemeente='" . $postcode->getGemeente() .
                "', postnr=" . $postcode->getPostnr() .
                ", kostprijs=" . $postcode->getKostprijs() .
                ", thuis_lev_ok=" . $postcode->getThuis_lev_ok() .
                " where id = " . $postcode->getId();
        $core = DBConfig::getInstance();        

        $core->dbh->exec($sql);
        //
        // Errorchecking before the update
        //
        if ($postcode->GetKostprijs() <0 or $postcode->getKostprijs() >100)
          throw new GemeenteKostprijs_WrongException();
        if (!is_numeric($postcode->getKostprijs()) )
          throw new GemeenteKostprijs_WrongException();
        //
        if ($postcode->getThuis_lev_ok() <0 or $postcode->getThuis_lev_ok() >1)
          throw new GemeenteThuislev_WrongException();
        if (!is_numeric($postcode->getThuis_lev_ok()) )
          throw new GemeenteThuislev_WrongException();
        //
        $dbh = null;
    }

    public static function Bestaat_Postnr_Gemeente_al ($postnr, $gemeente) {
        $core = DBConfig::getInstance();        

        $sql = "select id, postnr, gemeente, kostprijs, thuis_lev_ok
                  from postcodes
                 where gemeente = " . "'" . $gemeente . "'" . 
                  " and  postnr = ". $postnr;
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
           $post_gem_bestaat_al = FALSE;
           throw new GemeenteBestaatException();
        } else {
           $post_gem_bestaat_al = FALSE;
        }
        return $post_gem_bestaat_al;
    }    
}

?>