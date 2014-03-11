<?php

class Bestelling_dtl_extra {

    private static $idMap = array();
    private $id;
    private $best_dtl_id;
    private $product_id;
    private $prijs;
    private $korting;

    private function __construct($id, $best_dtl_id, $product_id, $prijs, 
                                 $korting, $product) {
        $this->id = $id;
        $this->best_dtl_id = $best_dtl_id;
        $this->product_id  = $product_id;
        $this->prijs       = $prijs;
        $this->korting     = $korting;
        $this->product     = $product;
    }

    public static function create($id,  $best_dtl_id, $product_id, $prijs, 
                                 $korting, $product) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Bestelling_dtl_extra ($id,  $best_dtl_id, $product_id, $prijs, 
                                 $korting, $product);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getBest_dtl_id() {
        return $this->best_dtl_id;
    }

    public function getProduct_id() {
        return $this->product_id;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getPrijs() {
        return $this->prijs;
    }

    public function getKorting() {
        return $this->korting;
    }


    public function setBest_dtl_id($best_dtl_id) {
        $this->best_dtl_id = $best_dtl_id;
    }

    public function setProduct_id($product_id) {
        $this->product_id = $product_id;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function setPrijs($prijs) {
        $this->prijs = $prijs;
    }

    public function setKorting($korting) {
        $this->korting = $korting;
    }
    
}

