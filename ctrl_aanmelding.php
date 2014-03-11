<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/postcodeservice.class.php");
require_once("exceptions/gebrexception.class.php");
//
// Action = process = controleer of gebruiker kan inloggen
//
if (isset($_GET["action"])) {
    if ($_GET["action"] == "process") 
    {
        //
        // Check user en paswoord bestaat nog niet, if so give an error, pass it onto presentation form 
        //
        try {GebruikerinfoService::Bestaat_Gebr_Pasw ($_POST["usernm"], sha1($_POST["pasw"]));}
        catch (GebrPaswBestaatNietException $tbe)
        {
            $error = "gebr_pasw_niet_ok";
            include("presentation/aanmelding_gebr.php");
            exit(0);
        }
        //
        // Check niet actief (geblokkeerd), if so give an error, pass it onto presentation form 
        //
        $actief = GebruikerinfoService::Get_Gebr_Actief ($_POST["usernm"]);
        if ($actief == 0)
        {
            $error = "gebr_actief_niet_ok";
            include("presentation/aanmelding_gebr.php");
            exit(0);
        }    
        // 
        // Aanmaken van de cookie
        //
        setcookie ("aangemeld", $_POST["usernm"], time() + 24 * 3600);        // we kunnen doorgaan en gebruiker toelaten 
        // 
        // Weghalen van de sessievariabele waarin de bestellingsid zit bewaard
        // 
        if (isset($_SESSION["best_id"]))
          {unset ($_SESSION["best_id"]);}         
        //
        // Check auth_level, if auth_level 0 common user, otherwise admin user
        //
        $auth_level = GebruikerinfoService::Get_Gebr_Level ($_POST["usernm"], sha1($_POST["pasw"]));
        if ($auth_level == 0)
        {
          header("location: ctrl_pizzashop_home.php");
          exit(0); 
        }
        else
        {
          // spring naar controller van beheerdersscherm
          header("location: ctrl_pizzashop_home_backend.php");
          exit(0); 
        }            
    }
} 
else 
{
    // if error then pass parameter $error to presentation form
    if (isset ($_GET["error"]) )
      { $error = $_GET["error"];}
    else
      {
        if (!isset($error))
        {$error=null;}
      }
    include("presentation/aanmelding_gebr.php");
} 
