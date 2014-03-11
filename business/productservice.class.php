<?php

require_once("data/productdao.class.php");

class ProductService {
    public static function toonProducten() {
        $lijst_producten = ProductDAO::getAll();
        return $lijst_producten;
    }
    public static function toonProductenCat($categorie) {
        $lijst_producten = ProductDAO::getAllCat($categorie);
        return $lijst_producten;
    }

    public static function verwijderProduct($id) {
        ProductDAO::Delete($id);
    }
    public static function updateProduct($id, $naam, $beschrijving, 
                                         $prijs, $korting, $categorie, $status  ) {
        $product = ProductDAO::getById($id);
        $product->setNaam($naam);
        $product->setBeschrijving($beschrijving);
        $product->setPrijs($prijs);
        $product->setKorting($korting);
        $product->setCategorie($categorie);
        $product->setStatus($status);
        ProductDAO::update($product);
    }
    
  public static function haalProductOp($id) {
        $product = ProductDAO::getById($id);
        return $product;
    }

  public static function voegNieuwProductToe($naam, $beschrijving, $prijs, $korting, $categorie, $status) 
    {
        $lijst_product = ProductDAO::Create($naam, $beschrijving, $prijs, $korting, $categorie, $status);
    }

  public static function Bestaat_Product_Id($id) {
        $product_bestaat = ProductDAO::Bestaat_product_id($id);
        return $product_bestaat;
    }

    
}

