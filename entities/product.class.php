<?php

class Product {

    private static $idMap = array();
    private $id;
    private $naam;
    private $beschrijving;
    private $prijs;
    private $korting;
    private $dt_aangemaakt;
    private $categorie;
    private $status;
    

    private function __construct($id, $naam, $beschrijving, $prijs, $korting, $categorie, $status) {
        $this->id = $id;
        $this->naam = $naam;
        $this->beschrijving = $beschrijving;
        $this->prijs = $prijs;
        $this->korting = $korting;
        $this->categorie = $categorie;
        $this->status = $status;
    }

    public static function create($id, $naam, $beschrijving, $prijs, $korting, $categorie, $status) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Product($id, $naam, $beschrijving, $prijs, $korting, $categorie, $status);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getNaam() {
        return $this->naam;
    }

    public function getBeschrijving() {
        return $this->beschrijving;
    }

    public function getPrijs() {
        return $this->prijs;
    }

    public function getKorting() {
        return $this->korting;
    }

    public function getDt_aangemaakt() {
        return $this->dt_aangemaakt;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setNaam($naam) {
        $this->naam = $naam;
    }

    public function setBeschrijving($beschrijving) {
        $this->beschrijving = $beschrijving;
    }

    public function setPrijs($prijs) {
        $this->prijs = $prijs;
    }

    public function setKorting($korting) {
        $this->korting = $korting;
    }

    public function setDt_aangemaakt($dt_aangemaakt) {
        $this->dt_aangemaakt = $dt_aangemaakt;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }

    
}

