<?php

require_once("data/dbconfig.class.php");
require_once("entities/product.class.php");

class ProductDAO {

    public static function getAll() {
        $lijst = array();
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "select id, naam, beschrijving, 
                       prijs, korting, categorie, status
                  from producten
                 order by categorie, naam asc";
        //$resultSet = $dbh->query($sql);
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $product = Product::create($rij["id"], $rij["naam"], $rij["beschrijving"], $rij["prijs"],
                                       $rij["korting"], $rij["categorie"], $rij["status"]  );
            $lijst [] = $product;
        }
        $dbh = null;
        return $lijst;
    }

    public static function getAllCat($categorie) {
        $lijst = array();
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "select id, naam, beschrijving, 
                       prijs, korting, categorie, status
                  from producten
                where  categorie = " . $categorie .   
                " and  status = 1 " . 
                " order by naam asc";
        //$resultSet = $dbh->query($sql);
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $product = Product::create($rij["id"], $rij["naam"], $rij["beschrijving"], $rij["prijs"],
                                       $rij["korting"], $rij["categorie"], $rij["status"]  );
            $lijst [] = $product;
        }
        $dbh = null;
        return $lijst;
    }

    public static function getById($id) {
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "select id, naam, beschrijving, 
                       prijs, korting, categorie, status
                  from producten
                 where id = ". $id;
        //$resultSet = $dbh->query($sql);
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        if (isset($rij["id"]))
          {$product   = Product::create($rij["id"], $rij["naam"], $rij["beschrijving"], $rij["prijs"],
                                        $rij["korting"], $rij["categorie"], $rij["status"]);}
        else
          {throw new ProdBestaatNietException();}
        $dbh = null;
        return $product;
    }

    public static function getByNaam($naam) {
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "select id, naam, beschrijving, 
                       prijs, korting, categorie, status
                  from producten
                 where naam = " . "'" . $naam . "'";
        //$resultSet = $dbh->query($sql);
        $resultSet = $core->dbh->query($sql);
        $rij = $resultSet->fetch();
        if ($rij) {
            $product   = Product::create($rij["id"], $rij["naam"], $rij["beschrijving"], $rij["prijs"],
                                     $rij["korting"], $rij["categorie"], $rij["status"]);
            $dbh = null;
        } else {
            $product = null;
        }
        return $product;
    }

    public static function Create($naam, $beschrijving, $prijs, $korting, $categorie, 
                                  $status) {
        $bestaand_Prod = self::getByNaam($naam);
        if (isset ($bestaand_Prod)) throw new ProdBestaatException();
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        // get system date and time to store in table 
        $date=date('y.m.d h:i:s');         
        $sql = "insert into producten (naam, beschrijving, dt_aangemaakt, prijs, korting, 
                                       categorie, status) 
                values ('" . $naam . "','" . $beschrijving . "','" . $date .  "', " . 
                          $prijs . ", " . $korting . ", " . $categorie . ", " . $status . 
                       ")";
                
        //
        // Errorchecking before the insert
        //
        if ($prijs <0 or $prijs >1000)
          throw new ProdPrijs_WrongException();
        if (!is_numeric($prijs))
          throw new ProdPrijs_WrongException();
        //        
        if ($korting <-100 or $korting >100)
          throw new ProdKorting_WrongException();
        if (!is_numeric($korting ))
          throw new ProdKorting_WrongException();
        //        
        if ($categorie <0 or $categorie >5)
          throw new ProdCategorie_WrongException();
        if (!is_numeric($categorie) )
          throw new ProdCategorie_WrongException();
        //        
        if ($status <0 or $status >1)
          throw new ProdStatus_WrongException();
        if (!is_numeric($status) )
          throw new ProdStatus_WrongException();
        //        
        $core->dbh->exec($sql);
        $Prod_Id = $core->dbh->lastInsertId();
        //$dbh->exec($sql);
        //$Prod_Id = $dbh->lastInsertId();
        $dbh = null;
        $product = Product::create($Prod_Id, $naam, $beschrijving, $prijs, $korting, 
                                  $categorie, $status); 
        return $product; 
    }
    
    public static function Delete($id) {
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "delete from producten where id = ". $id;
        $core->dbh->exec($sql);
        //$dbh->exec($sql);
        $dbh = null;
    }

    public static function update($product) {
        $bestaande_prod = self::getByNaam($product->getNaam() );
        if (isset ($bestaande_prod) && $bestaande_prod->getId() != $product->getId() )
        {
            throw new ProdBestaatException();
        }
        $sql = "update producten set naam='" . $product->getNaam() .
                "', beschrijving=" . "'" . $product->getBeschrijving() .
                "', prijs=" . $product->getPrijs() .
                ", korting=" . $product->getKorting() .
                ", categorie=" . $product->getCategorie() .
                ", status=" . $product->getStatus() .
                ", korting=" . $product->getKorting() .
                " where id = " . $product->getId();
        //
        // Errorchecking before the update
        //
        if ($product->getPrijs() <0 or $product->getPrijs() >1000)
          throw new ProdPrijs_WrongException();
        if (!is_numeric($product->getPrijs()) )
          throw new ProdPrijs_WrongException();
        //        
        if ($product->getKorting() <-100 or $product->getKorting() >100)
          throw new ProdKorting_WrongException();
        if (!is_numeric($product->getKorting()) )
          throw new ProdKorting_WrongException();
        //        
        if ($product->getCategorie() <0 or $product->getCategorie() >5)
          throw new ProdCategorie_WrongException();
        if (!is_numeric($product->getCategorie()) )
          throw new ProdCategorie_WrongException();
        //        
        if ($product->getStatus() <0 or $product->getStatus() >1)
          throw new ProdStatus_WrongException();
        if (!is_numeric($product->getStatus()) )
          throw new ProdStatus_WrongException();
        //        
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        //$dbh->exec($sql);
        $core->dbh->exec($sql);
        $dbh = null;
    }

    public static function Bestaat_product_id($id) {
        $core = DBConfig::getInstance();        
        /* $dbh = new PDO(DBConfig::$DB_CONNSTRING,
                        DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); */
        $sql = "select id, naam, beschrijving, 
                       prijs, korting, categorie, status
                  from producten
                 where id = ". $id;
        //$resultSet = $dbh->query($sql);
        $resultSet = $core->dbh->query($sql);
        $rij       = $resultSet->fetch();
        if ($rij) {
           $product_bestaat = TRUE;
        } else {
           $product_bestaat = FALSE;
           throw new ProdBestaatNietException();
        }
        $dbh = null;
        return $product_bestaat;
    }
}

?>