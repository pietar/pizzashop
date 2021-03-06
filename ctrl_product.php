<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/productservice.class.php");
require_once("exceptions/prodexception.class.php");
//
// Check auth_level, if auth_level 0 common user, otherwise admin user
//
if (isset($_COOKIE["aangemeld"])) {
    $auth_level = GebruikerinfoService::Get_Gebr_Level_User($_COOKIE["aangemeld"]);
    if ($auth_level == 0) {
        header("location: ctrl_pizzashop_home.php");
        exit(0);
    }
} 
else {
    $login = "Gelieve terug aan te melden";
    header("location: ctrl_pizzashop_home.php");
    exit(0);
}
//
// Check action to do
//
// change = wijzig product-info
// update = schrijf aangepaste info naar db
// new    = geef nieuwe product-postcode in
// insert = schrijf nieuwe info naar db
// delete = verwijderen info
// error_upd_prod = error bij updaten
// error_ins_prod = error bij toevoegen
// 
if (isset($_GET["action"])) {
    if ($_GET["action"] == "change") 
    {
        // show data of this product
        //
        if (isset ($_GET["id"]))
        {
            try {$product = ProductService::haalProductOp($_GET["id"]); }
            catch (ProdBestaatNietException $tbe)
            {
                $error = "Product bestaat niet";
                header("location: ctrl_product.php?action=error_ins_prod&error=".$error);
                exit(0);      
            }
        }        
        include("presentation/pizzashop_update_product.php");
        exit(0);
    }
    elseif ($_GET["action"] == "update") 
    {
        //
        //  update product in db
        //
        if (isset ($_GET["id"]))
        {
            //
            // bewaren van aantal gegevens zodat terug gegevens kunnen getoond worden wanneer het fout loopt
            //
            $_SESSION ["err_product_id"]        = $_GET["id"];
            $_SESSION ["err_product_naam"]      = $_POST["Naam"];
            $_SESSION ["err_product_beschrijving"] = $_POST["Beschrijving"];
            $_SESSION ["err_product_prijs"]     = $_POST["Prijs"];
            $_SESSION ["err_product_korting"]   = $_POST["Korting"];
            $_SESSION ["err_product_categorie"] = $_POST["Categorie"];
            $_SESSION ["err_product_status"]    = $_POST["Status"];
            //
            try {
                ProductService::updateProduct($_GET["id"]    , $_POST["Naam"]   , $_POST["Beschrijving"], 
                                              $_POST["Prijs"], $_POST["Korting"], $_POST["Categorie"], 
                                              $_POST["Status"]);
                header("location: ctrl_product.php");
                exit(0);
                }
            catch (ProdBestaatException $tbe)
            {
                $error = "Product bestaat reeds";
                header ("location: ctrl_product.php?action=error_upd_prod&error=".$error."&id=".$_GET["id"]);
                exit(0);
            }        
            catch (ProdPrijs_WrongException $tbe)
            {
                $error = "Prijs is fout, moet tussen 0 en 1000 liggen";
                header ("location: ctrl_product.php?action=error_upd_prod&error=".$error."&id=".$_GET["id"]);
                exit(0);
            }        
            catch (ProdKorting_WrongException $tbe)
            {
                $error = "Korting is fout, moet tussen 0 en 10 liggen";
                header ("location: ctrl_product.php?action=error_upd_prod&error=".$error."&id=".$_GET["id"]);
                exit(0);
            }        
            catch (ProdCategorie_WrongException $tbe)
            {
                $error = "Categorie moet tussen 1 en 5 liggen";
                header ("location: ctrl_product.php?action=error_upd_prod&error=".$error."&id=".$_GET["id"]);
                exit(0);
            }        
            catch (ProdStatus_WrongException $tbe)
            {
                $error = "Status is fout, moet 0 of 1 zijn";
                header ("location: ctrl_product.php?action=error_upd_prod&error=".$error."&id=".$_GET["id"]);
                exit(0);
            }        
        }        
        include("presentation/pizzashop_update_product.php");
        exit(0);
    }        
    elseif ($_GET["action"] == "new") 
    {
        // add new product
        //
        include("presentation/pizzashop_insert_product.php");
        exit(0);
    }
    elseif ($_GET["action"] == "insert") 
    {
        // 
        // bijhouden oude waardes, zodat deze getoond worden 
        //   men hoeft niet alles opnieuw in te voeren
        //
        $_SESSION ["err_product_naam"]         = $_POST["Naam"];
        $_SESSION ["err_product_prijs"]        = $_POST["Prijs"];
        $_SESSION ["err_product_korting"]      = $_POST["Korting"];
        $_SESSION ["err_product_categorie"]    = $_POST["Categorie"];
        $_SESSION ["err_product_status"]       = $_POST["Status"];
        $_SESSION ["err_product_beschrijving"] = $_POST["Beschrijving"];
        // 
        // add new product in db
        //
        try {
            ProductService::voegNieuwProductToe($_POST["Naam"], $_POST["Beschrijving"], $_POST["Prijs"], $_POST["Korting"], $_POST["Categorie"], $_POST["Status"]);
            header("location: ctrl_product.php");
            exit(0);
        } catch (ProdBestaatException $tbe) {
            $error = "Product bestaat reeds";
            header("location: ctrl_product.php?action=error_ins_prod&error=" . $error);
            exit(0);
        } catch (ProdPrijs_WrongException $tbe) {
            $error = "Prijs is fout, moet tussen 0 en 1000 liggen";
            header("location: ctrl_product.php?action=error_ins_prod&error=" . $error);
            exit(0);
        } catch (ProdKorting_WrongException $tbe) {
            $error = "Korting is fout, moet tussen 0 en 10 liggen";
            header("location: ctrl_product.php?action=error_ins_prod&error=" . $error);
            exit(0);
        } catch (ProdCategorie_WrongException $tbe) {
            $error = "Categorie moet tussen 1 en 5 liggen";
            header("location: ctrl_product.php?action=error_ins_prod&error=" . $error);
            exit(0);
        } catch (ProdStatus_WrongException $tbe) {
            $error = "Status is fout, moet 0 of 1 zijn";
            header("location: ctrl_product.php?action=error_ins_prod&error=" . $error);
            exit(0);
        }
    }
    elseif ($_GET["action"] == "delete") 
    {
        //
        // delete product
        //
        ProductService::verwijderProduct ($_GET["id"] );
        $producten = ProductService::toonProducten(); 
        include("presentation/pizzashop_backend_product.php");
        exit(0);
    } 
    elseif ($_GET["action"] == "error_upd_prod") 
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
        } else {
            $error = null;
        }
        if (isset($_GET["id"])) {
            $product_id = $_GET["id"];
        } else {
            $product_id = null;
        }
        $product = ProductService::haalProductOp($_GET["id"]);
        //
        include("presentation/pizzashop_update_product.php");
        exit(0);        
    } 
    elseif ($_GET["action"] == "error_ins_prod") 
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
        } else {
            $error = null;
        }
        //
        include("presentation/pizzashop_insert_product.php");
        exit(0);        
    } 
}
else 
{
    // if error then pass parameter $error to presentation form
    if (isset ($_GET["error"]) )
      { $error = $_GET["error"];
      }
    else
      {$error = null;}
    //
    if (isset ($_GET["error_bij_update"]) )
      { $error = "Productnaam bestaat al";  
        $product = ProductService::haalProductOp($_GET["id"]);
        include("presentation/pizzashop_update_product.php");
        exit(0);
      }
    elseif (isset ($_GET["error_bij_insert"]) )
    {
        $error = "Productnaam bestaat al";  
        include("presentation/pizzashop_insert_product.php");
        exit(0);
    }  
    elseif (!isset ($error))
      {$error = null;}
    //  
    $producten = ProductService::toonProducten(); 
    //
    include("presentation/pizzashop_backend_product.php");     
    //    
} 
