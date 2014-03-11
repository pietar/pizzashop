<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/productservice.class.php");
require_once("business/postcodeservice.class.php");
require_once("business/bedrijfsinfoservice.class.php");
//
require_once("business/bestelling_hdrservice.class.php");
require_once("business/bestelling_dtlservice.class.php");
require_once("business/bestelling_dtlextraservice.class.php");
//
//
// action = toon_ovrz = toon overzicht bestellingen voor leveringen (sort op uur, postcode, gemeente)
//
if (isset($_GET["action"])) {
    if ($_GET["action"] == "toon_ovrz") 
    {
       //
       // Toon bestellingen, selectie op datum en uur en postcode en 
       //     gesorteerd op datum, uur en postcode
       //
       // Bewaren oude waardes van datums
       //
       $_SESSION ["err_date1"] = $_POST["Datum_uur_lev1"];
       $_SESSION ["err_date2"] = $_POST["Datum_uur_lev2"];
       // 
       switch ($_POST["Keuze_lijst"])
       {
           case 1 :
           //
           //  overzicht planning van de leveringen, gesorteerd per datum, gemeente 
           //    
           {  
             try {
                    $best_ovrz = Bestelling_hdrService::toonBestellingen_date
                                    ($_POST["Datum_uur_lev1"], $_POST["Datum_uur_lev2"]);
                 } 
             catch (Bestelling_Uurlev_WrongException $tbe) 
                 {
                     $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
                     header("location: ctrl_ovrz_bestellingen.php?action=add&error=" . $error);
                     exit(0);
                 }
             include("presentation/pizzashop_ovrz_bestel_date.php");     
             exit(0);
             break;
           }
           case 2 :
           //
           //  overzicht bestellingen per klant 
           //    
           {  
             try {
                    $best_ovrz = Bestelling_hdrService::toonBestellingen_date
                                    ($_POST["Datum_uur_lev1"], $_POST["Datum_uur_lev2"]);
                 } 
             catch (Bestelling_Uurlev_WrongException $tbe) 
                 {
                     $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
                     header("location: ctrl_ovrz_bestellingen.php?action=add&error=" . $error);
                     exit(0);
                 }
             include("presentation/pizzashop_ovrz_bestel_date.php");     
             exit(0);
             break;
           }
           case 3 :
           //
           //  overzicht bestellingen per pizza 
           //    
           {  
             try {
                    $best_ovrz = Bestelling_hdrService::toonBestellingen_date
                                    ($_POST["Datum_uur_lev1"], $_POST["Datum_uur_lev2"]);
                 } 
             catch (Bestelling_Uurlev_WrongException $tbe) 
                 {
                     $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
                     header("location: ctrl_ovrz_bestellingen.php?action=add&error=" . $error);
                     exit(0);
                 }
             include("presentation/pizzashop_ovrz_bestel_date.php");     
             exit(0);
             break;
           }
        }
    }   
}
//    
if (isset($_GET["error"]))
  $error = $_GET["error"];
elseif (!isset($error))
  $error = "";
// 
$postcodes = PostcodeService::getPostcodes_in_array(); 
$bedrijf   = BedrijfsinfoService::toonBedrijfInfo();
//
include("presentation/pizzashop_ovrz_bestellingen.php");     


 
