<?php

require_once("data/dbconfig.class.php");
require_once("entities/gastenboek.class.php");

class GastenboekDAO {

    public static function getAll() {
        $lijst = array();
        $core = DBConfig::getInstance();        
        $sql = "select id, usernm, boodschap, dt_aangemaakt
                  from gastenboek 
                 order by dt_aangemaakt desc";
        $resultSet = $core->dbh->query($sql);
        foreach ($resultSet as $rij) {
            $gastenboek = Gastenboek::create($rij["id"], $rij["usernm"], $rij["boodschap"], $rij["dt_aangemaakt"]);
            $lijst [] = $gastenboek;
        }
        return $lijst;
    }

    public static function Create($usernm, $boodschap, $dt_aangemaakt) {
        $core = DBConfig::getInstance();        
        $sql = "insert into gastenboek (usernm, boodschap, dt_aangemaakt) 
                values ('" . $usernm . "', " . $boodschap . "', " . $dt_aangemaakt . 
                       ")";
                
        $core->dbh->exec($sql);
        $Gastb_Id = $core->dbh->lastInsertId();
        $gastenboek = Gebruiker::create($Gastb_Id, $naam, $boodschap, $dt_aangemaakt); 
        return $gastenboek; 
    }
    
    public static function Delete($id) {
        $core = DBConfig::getInstance();        
        $sql = "delete from gastenboek where id = ". $id;
        $core->dbh->exec($sql);
    }

}

?>