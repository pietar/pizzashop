<?php

class Postcode {

    private static $idMap = array();
    private $id;
    private $postnr;
    private $gemeente;
    private $kostprijs;
    private $thuis_lev_ok;

    private function __construct($id, $postnr, $gemeente, $kostprijs, $thuis_lev_ok) {
        $this->id = $id;
        $this->postnr = $postnr;
        $this->gemeente = $gemeente;
        $this->kostprijs = $kostprijs;
        $this->thuis_lev_ok = $thuis_lev_ok;
    }

    public static function create($id, $postnr, $gemeente, $kostprijs, $thuis_lev_ok) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Postcode($id, $postnr, $gemeente, $kostprijs, $thuis_lev_ok);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getPostnr() {
        return $this->postnr;
    }

    public function getGemeente() {
        return $this->gemeente;
    }

    public function getKostprijs() {
        return $this->kostprijs;
    }

    public function getThuis_lev_ok() {
        return $this->thuis_lev_ok;
    }

    public function setPostnr($postnr) {
        $this->postnr = $postnr;
    }

    public function setGemeente($gemeente) {
        $this->gemeente = $gemeente;
    }

    public function setKostprijs($kostprijs) {
        $this->kostprijs = $kostprijs;
    }

    public function setThuis_lev_ok($thuis_lev_ok) {
        $this->thuis_lev_ok = $thuis_lev_ok;
    }
    
}

