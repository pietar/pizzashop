<?php

class Bestelling_dtl {

    private static $idMap = array();
    private $id;
    private $best_id;
    private $product_id;
    private $aantal;
    private $prijs;
    private $korting;
    private $product;
    private $bestel_dtl_extra;
    
    private function __construct($id, $best_id, $product_id, $aantal, $prijs, 
                                 $korting, $product, $bestel_dtl_extra) {
        $this->id = $id;
        $this->best_id = $best_id;
        $this->product_id = $product_id;
        $this->aantal = $aantal;
        $this->prijs = $prijs;
        $this->korting = $korting;
        $this->product = $product;
        $this->bestel_dtl_extra = $bestel_dtl_extra;
    }

    public static function create($id,  $best_id, $product_id, $aantal, $prijs, $korting, $product, $bestel_dtl_extra) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Bestelling_dtl($id,  $best_id, $product_id, $aantal, $prijs, $korting, $product, $bestel_dtl_extra);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getBest_id() {
        return $this->best_id;
    }

    public function getProduct_id() {
        return $this->product_id;
    }

    public function getAantal() {
        return $this->aantal;
    }

    public function getPrijs() {
        return $this->prijs;
    }

    public function getKorting() {
        return $this->korting;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getBestel_dtl_extra() {
        return $this->bestel_dtl_extra;
    }
    
    public function setBest_id($best_id) {
        $this->best_id = $best_id;
    }

    public function setProduct_id($product_id) {
        $this->product_id = $product_id;
    }

    public function setProduct($product) {
        $this->product = $product;
    }
    
    public function setAantal($aantal) {
        $this->aantal = $aantal;
    }
    public function setPrijs($prijs) {
        $this->prijs = $prijs;
    }

    public function setKorting($korting) {
        $this->korting = $korting;
    }

    public function setBestel_dtl_extra($bestel_dtl_extra) {
        $this->bestel_dtl_extra = $bestel_dtl_extra;
    }
    
    
}

