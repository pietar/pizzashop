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
        header("header: ctrl_pizzashop_bd_info.php");
        exit(0);
    }
} 
else 
{
    // if error then pass parameter $error to presentation form
    if (isset ($_GET["error"]) )
      { $error = $_GET["error"];
        print ("error wordt gevonden, error doorgeven naar de pres.form");
      }
            
      else
      {$error = null;}
    //  
    $producten = ProductService::toonProducten(); 
    //
    include("presentation/pizzashop_backend_product.php");     
    //    
} 
