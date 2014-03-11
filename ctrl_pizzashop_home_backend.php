
<?php 
    require_once("business/bedrijfsinfoservice.class.php"); 
    require_once("business/gebruikersservice.class.php"); 
    require_once("business/postcodeservice.class.php");
    //
    // Check auth_level, if auth_level 0 common user, otherwise admin user
    //
    if (isset ($_COOKIE["aangemeld"] ))
    {
      $auth_level = GebruikerinfoService::Get_Gebr_Level_User ($_COOKIE["aangemeld"] );
      if ($auth_level == 0)
      {
         header("location: ctrl_pizzashop_home.php");
         exit(0); 
      }  
    }
    else
    {
       $login = "Gelieve terug aan te melden";  
       header("location: ctrl_pizzashop_home.php");
       exit(0); 
    }
    
    //

    $bedrijfsinfo = BedrijfsinfoService::toonBedrijfInfo(); 
    $postcodes    = PostcodeService::getPostcodes_in_array();     
    //
    include("presentation/pizzashop_backend_bedrijf.php"); 
?>

