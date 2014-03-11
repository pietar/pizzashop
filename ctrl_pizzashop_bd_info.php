<?php
session_start();
require_once("business/bedrijfsinfoservice.class.php");
require_once("business/postcodeservice.class.php");
if (isset($_GET["action"])) {
    if ($_GET["action"] == "process") 
    {
        //
        BedrijfsinfoService::updateBedrijf
                   ($_POST["Id"],          $_POST["Naam"]       , $_POST["Adres"], 
                    $_POST["Postcode_id"], $_POST["Telefoon"]   , $_POST["Gsm"], 
                    $_POST["Email"]      , $_POST["Openinguren"], $_POST["Alg_info"], 
                    $_POST["Promotie"]   , $_POST["Lev_vw"],      $_POST["Faq_info"]);
        header("location: ctrl_pizzashop_home_backend.php");
        exit(0);
    }
} 
else 
{
    // if error then pass parameter $error to presentation form
    if (isset ($_GET["error"]) )
      { $error = $_GET["error"];}
      else
      {$error = null;}
    //  
    $bedrijfsinfo = BedrijfsinfoService::toonBedrijfInfo(); 
    $postcodes = PostcodeService::getPostcodes_in_array(); 
    //
    include("presentation/pizzashop_backend_bedrijf.php");     
    //    
} 
