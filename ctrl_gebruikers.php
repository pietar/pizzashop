<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/postcodeservice.class.php");
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
// change = wijzigen van gebruikersinfo
// update = gebruikersinfo is gewijzigd, wegschrijven gegevens in db
// new    = nieuwe gebruikersinfo invoeren
// insert = wegschrijven van nieuwe gebruikersinfo
// delete = verwijderen gebruiker
// error_gebr = errors bij één van de acties
//
if (isset($_GET["action"])) {
    if ($_GET["action"] == "change") 
    {
        // show data of this product
        //
        if (isset ($_GET["id"]))
        {
            $gebr = GebruikerinfoService::haalGebrOp($_GET["id"]);
            if (isset ($_GET["error"] ))
                {$error = $_GET["error"];}
            else
                {$error=null;}
        }        
        $postcodes  = PostcodeService::getPostcodes_in_array();     
        include("presentation/pizzashop_update_gebr.php");
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
            // bijhouden oude waardes, zodat deze getoond worden 
            //   men hoeft niet alles opnieuw in te voeren
            //
            $_SESSION ["err_klant_naam"]       = $_POST["Naam"];
            $_SESSION ["err_klant_level_auth"] = $_POST["Level_auth"];
            $_SESSION ["err_klant_adres"]      = $_POST["Adres"];
            $_SESSION ["err_klant_email"]      = $_POST["Email"];
            $_SESSION ["err_klant_telefoon"]   = $_POST["Telefoon"];
            $_SESSION ["err_klant_actief"]     = $_POST["Actief"];
            $_SESSION ["err_klant_postcode_id"]= $_POST["Postcode_id"];
            $_SESSION ["err_klant_korting"]    = $_POST["Korting"];
            $_SESSION ["err_klant_opm_extra"]  = $_POST["Opm_extra"];
            // 
            try {GebruikerinfoService::updateGebr($_GET["id"]  , $_POST["Naam"] , 
                                             $_POST["Level_auth"], 
                                             $_POST["Adres"] , $_POST["Email"]     , $_POST["Telefoon"], 
                                             $_POST["Actief"],$_POST["Postcode_id"], $_POST["Korting"],
                                             $_POST["Opm_extra"]  );}
            catch (GebrLevel_Auth_WrongException $tbe)
            {
               $error = "Level is niet ok";
               header("location: ctrl_gebruikers.php?action=error_gebr&error=".$error."&id=".$_GET["id"]);
               exit(0);                
            }                                             
            catch (GebrActief_WrongException $tbe)
            {
               $error = "Actief moet 0 of 1 zijn";
               header("location: ctrl_gebruikers.php?action=error_gebr&error=".$error."&id=".$_GET["id"]);
               exit(0);                
            }                       
            catch (GebrKorting_WrongException $tbe)
            {
               $error = "Korting moet tussen -10 en 50 Euro liggen";
               header("location: ctrl_gebruikers.php?action=error_gebr&error=".$error."&id=".$_GET["id"]);
               exit(0);                
            }                       
            //
            // geen fout,  ....
            //
            header("location: ctrl_gebruikers.php");
            exit(0);
        }  
        include("presentation/pizzashop_update_gebr.php");
        exit(0);
    }        
     elseif ($_GET["action"] == "new") 
    {
        // add new user
        //
        $postcodes  = PostcodeService::getPostcodes_in_array();     
        include("presentation/pizzashop_insert_gebr.php");
        exit(0);
    }
    elseif ($_GET["action"] == "insert") 
    {
        // 
        // add new user in db
        //
        // get system date and time to store in table 
        $date=date('y.m.d h:i:s'); 
        //
        // Check user bestaat nog niet, if so give an error, pass it to presentation form 
        //
        try {GebruikerinfoService::Bestaat_Nieuwe_Gebr_al ($_POST["usernm"]);}
        catch (GebrBestaatException $tbe)
        {
            $error = "gebr_exists";
            //  
            // get all postcodes in array to show dropdown list in user registration form
            //
            $postcodes = PostcodeService::getPostcodes_in_array(); 
            include("presentation/pizzashop_insert_gebr.php");
            exit(0);
        }
        // we kunnen doorgaan en gebruiker aanmaken
        GebruikerinfoService::voegNieuweGebrToe
              ($_POST["naam"]       , $_POST["usernm"] , sha1 ($_POST["pasw"]), 
               0                    , $_POST["adres"]  , $_POST["email"], 
               $_POST["telefoon"]   , 1                , $date,                     
               $_POST["postcode_id"], 0 , 
               $_POST["opm_extra"],                     
               $_POST["btwnr"]
              );
        header("location: ctrl_gebruikers.php");
        exit(0);         
        
    }    
    elseif ($_GET["action"] == "delete") 
    {
        //
        // delete product
        //
        GebruikerinfoService::verwijderGebr ($_GET["id"] );
        $gebruikers = GebruikerinfoService::toonGebruikers(); 
        $postcodes  = PostcodeService::getPostcodes_in_array();     
        include("presentation/pizzashop_backend_gebruiker.php");     
        exit(0);
    } 
    elseif ($_GET["action"] == "error_gebr") 
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
        } else {
            $error = null;
        }
        if (isset($_GET["id"])) {
            $gebr_id = $_GET["id"];
        } else {
            $gebr_id = null;
        }
        // 
        // give error and show data of this product
        //
        if (isset ($gebr_id))
        {
            $gebr = GebruikerinfoService::haalGebrOp($gebr_id);
            if (isset ($_GET["error"] ))
                {$error = $_GET["error"];}
            else
                {$error=null;}
        }        
        $postcodes  = PostcodeService::getPostcodes_in_array();     
        //
        include("presentation/pizzashop_update_gebr.php");
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
    $gebruikers = GebruikerinfoService::toonGebruikers(); 
    $postcodes  = PostcodeService::getPostcodes_in_array();     
    //
    include("presentation/pizzashop_backend_gebruiker.php");     
    //    
} 
