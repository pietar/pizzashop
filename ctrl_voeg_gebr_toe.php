<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/postcodeservice.class.php");
require_once("exceptions/gebrexception.class.php");
if (isset($_GET["action"])) {
    if ($_GET["action"] == "process") 
    {
        // get system date and time to store in table 
        $date=date('y.m.d h:i:s'); 
        //
        // Check user bestaat nog niet, if so give an error, pass it to presentation form 
        //
        try {GebruikerinfoService::Bestaat_Nieuwe_Gebr_al ($_POST["usernm"]);}
        catch (GebrBestaatException $tbe)
        {
            $error = "gebr_exists";
            // get all postcodes in array to show dropdown list in user registration form
            $postcodes = PostcodeService::getPostcodes_in_array(); 
            include("presentation/nieuw_gebr_form.php");
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
        setcookie ("aangemeld", $_POST["usernm"], time() + 3600);
        header("location: ctrl_pizzashop_home.php");
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
    // get all postcodes in array to show dropdown list in user registration form
    //
    $postcodes = PostcodeService::getPostcodes_in_array(); 
    include("presentation/nieuw_gebr_form.php");
} 
