<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/postcodeservice.class.php");
require_once("exceptions/gemeenteexception.class.php");
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
// change = wijzig gemeente-info
// update = schrijf aangepaste info naar db
// new    = geef nieuwe gemeente-postcode in
// insert = schrijf nieuwe info naar db
// delete = verwijderen info
// error_upd_gem = error bij updaten
// error_ins_gem = error bij toevoegen
//
if (isset($_GET["action"])) {
    if ($_GET["action"] == "change") 
    {
        // show data of this product
        //
        if (isset ($_GET["id"]))
        {
            $pc = PostcodeService::haalPostcodeOp($_GET["id"]);
            if (isset ($_GET["error"] ))
                {$error = $_GET["error"];}
            else
                {$error=null;}
        }        
        include("presentation/pizzashop_update_postcode.php");
        exit(0);
    }
    elseif ($_GET["action"] == "update") 
    {
        //
        //  update product in db
        //
        if (isset ($_GET["id"]))
        {
            try {
            PostcodeService::updatePostcode($_GET["id"]    , $_POST["Postnr"] , $_POST["Gemeente"], 
                                            $_POST["Kostprijs"], $_POST["Thuis_lev_ok"] );}
            catch (GemeenteBestaatException $tbe)                                            
            {
                $error = "Postcode-gemeente bestaat al";
                header("location: ctrl_plaatsen.php?action=error_upd_gem&error=".$error."&id=".$_GET["id"]);
                exit(0);                           
            }
            catch (GemeenteKostprijs_WrongException $tbe)
            {
                $error = "Kostprijs moet tussen 0 en 100 liggen";
                header("location: ctrl_plaatsen.php?action=error_upd_gem&error=".$error."&id=".$_GET["id"]);
                exit(0);                           
            }
            catch (GemeenteThuislev_WrongException $tbe)
            {
                $error = "Thuislevering is ofwel 0 ofwel 1";
                header("location: ctrl_plaatsen.php?action=error_upd_gem&error=".$error."&id=".$_GET["id"]);
                exit(0);                           
            }
            header("location: ctrl_plaatsen.php");
            exit(0);
        }        
        include("location: ctrl_plaatsen.php");
        exit(0);
    }        
     elseif ($_GET["action"] == "new") 
    {
        // 
        // add new postcode-gemeente
        //
        include("presentation/pizzashop_insert_postcode.php");
        exit(0);
    }
    elseif ($_GET["action"] == "insert") 
    {
        // 
        // voeg toe in db
        //
        try {PostcodeService::Bestaat_Postnr_Gemeente_al ($_POST["postnr"], $_POST["gemeente"]);}
        catch (GemeenteBestaatException $tbe)
        {
            $error = "Postcode ".$_POST["postnr"]." en gemeente ". $_POST["gemeente"]. " bestaat reeds";
            header("location: ctrl_plaatsen.php?action=error_ins_gem&error=".$error);
            exit(0);                           
        }
        // we kunnen doorgaan en in db toevoegen
        try {PostcodeService::voegPostcodeToe
              ($_POST["postnr"]     , $_POST["gemeente"] , $_POST["kostprijs"], 
               $_POST["thuis_lev_ok"]
              );}
        catch (GemeenteKostprijs_WrongException $tbe)
        {
            $error = "Kostprijs moet tussen 0 en 100 liggen";
            header("location: ctrl_plaatsen.php?action=error_ins_gem&error=".$error);
            exit(0);                           
        }
        catch (GemeenteThuislev_WrongException $tbe)
        {
            $error = "Thuislevering is ofwel 0 ofwel 1";
            header("location: ctrl_plaatsen.php?action=error_ins_gem&error=".$error);
            exit(0);                           
        }
        header("location: ctrl_plaatsen.php");
        exit(0);         
        
    }    
    elseif ($_GET["action"] == "delete") 
    {
        //
        // delete product
        //
        PostcodeService::verwijderPostcode($_GET["id"] );
        $postcodes = PostcodeService::toonPostcodes(); 
        include("presentation/pizzashop_backend_postcodes.php");     
        exit(0);
    } 
    elseif ($_GET["action"] == "error_upd_gem") 
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
        } else {
            $error = null;
        }
        if (isset($_GET["id"])) {
            $pc_id = $_GET["id"];
        } else {
            $pc_id = null;
        }
        $pc = PostcodeService::haalPostcodeOp($pc_id);
        //
        include("presentation/pizzashop_update_postcode.php");
        exit(0);
    }
    elseif ($_GET["action"] == "error_ins_gem") 
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
        } else {
            $error = null;
        }
        //
        include("presentation/pizzashop_insert_postcode.php");
        exit(0);
    }
}
else 
{
    //
    // toon overzicht postcodes
    //
    $postcodes = PostcodeService::toonPostcodes(); 
    include("presentation/pizzashop_backend_postcodes.php");     
} 
