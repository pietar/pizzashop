<?php
session_start();
require_once("business/gebruikersservice.class.php");
require_once("business/productservice.class.php");
require_once("business/bestelling_hdrservice.class.php");
require_once("business/bestelling_dtlservice.class.php");
require_once("business/bestelling_dtlextraservice.class.php");
//
require_once("exceptions/prodexception.class.php");
require_once("exceptions/gemeenteexception.class.php");
require_once("exceptions/gebrexception.class.php");
require_once("exceptions/bestellingenexception.class.php");
//
// Check action to do
//    Show_product   = toon productinfo, keer daarna terug naar zelfde controller
//    add            = product_id gekozen en kies aantal en eventueel extra's
//    add_winkelmand = extra's en aantal is gekozen, voeg toe aan winkelmandje
//    confirmed      = winkelmandje is bevestigd, verwerk bestelling
//    show_previous  = toon vorige bestelling
//    delete         = verwijderen van 1 bestellijn
//    
// No action = toon bestellingsmogelijkheden
//
if (isset($_GET["action"])) {
    if ($_GET["action"] == "show_product") 
    {
        // 
        // toon langere omschrijving van dit product
        //
        if (isset ($_GET["product_id"]))
        {
            try {$product_bestaat = ProductService::Bestaat_Product_Id($_GET["product_id"]);}
            catch (ProdBestaatNietException $tbe)
            {
               $error = "Product bestaat niet ! ";
               // 
               header("location: ctrl_pizzashop_bestel.php?error=".$error);
               exit(0);
            } 
            $product = ProductService::haalProductOp($_GET["product_id"]);
        }        
        include("presentation/pizzashop_show_product_info.php");
        exit(0);
    }
    elseif ($_GET["action"] == "add") 
    {
        //  gebruiker heeft op winkelmandje geclickt en indien alles ok, 
        //     wordt navigatie naar het scherm met de extra's geleid
        //     
        //  check of gebruiker aangemeld is en mag bestellen
        //
        if (isset ($_COOKIE["aangemeld"] ))
          {
           $login = "aangemeld met ".$_COOKIE["aangemeld"];
           //        
           //  check of gebruiker geblokkeerd is en niet meer mag bestellen
           //
           $actief = GebruikerinfoService::Get_Gebr_Actief($_COOKIE["aangemeld"]);
           if (isset ($actief) and $actief == 0)
             {$error = "Gelieve eerst uw openstaande saldo te betalen";}
          }
        else
          {$error = "Gelieve u eerst aan te melden vooraleer de bestelling te registreren";
           // eerst aanmelden, + info over gekozen productid (dit onthouden voor later)
           header("location: ctrl_aanmelding.php?error=".$error);
           exit(0);
          } 
        //
        if (isset($_SESSION["best_id"])) {
            //
            //  hoofdgegevens van de bestelling  
            // indien bestelling is afgewerkt, dan geen nieuwe bestellingen meer doen
            //
            $best_hdr_winkelm = Bestelling_hdrService::GetById($_SESSION["best_id"]);
            if ($best_hdr_winkelm->GetStatus() == 1) {
                $error = "Uw bestelling is genoteerd en afgewerkt";
                if (isset($_COOKIE["aangemeld"])) {
                    $gebr = GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);
                }
                // 
                // haal productinfo op zodat dit kan getoond worden op scherm
                //
                $producten = ProductService::toonProductenCat(1);
                $producten_drank = ProductService::toonProductenCat(3);
                //
                include("presentation/pizzashop_bestel_producten.php");
                exit(0);
            }
        }
        //  
        // Alles ok, we gaan naar scherm waar extra's kunnen toegevoegd worden en aantal pizza's  
        //
        // Haal productinfo op en geef het door naar presentatieform
        //
        $prod = ProductService::haalProductOp($_GET["product_id"]);
        //
        // Controle of product_id bestaat of niet ?, indien niet foutmelding en terug 
        //
        $product_id      = $_GET["product_id"];
        try {$product_bestaat = ProductService::Bestaat_Product_Id($product_id);}
        catch (ProdBestaatNietException $tbe)
        {
            $error = "Product bestaat niet ! ";
            // 
            header("location: ctrl_pizzashop_bestel.php?error=".$error);
            exit(0);
        } 
        //
        $producten_extra = ProductService::toonProductenCat(2);
        //
        // Is thuislevering mogelijk ?, toon dit op scherm indien niet mogelijk
        //
        $gebr = GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);
        //
        if (isset($_GET["error"]))
        {$error = $_GET["error"];}
        //        
        include("presentation/pizzashop_bestel_producten_extra.php");
        exit(0);
    }
    elseif ($_GET["action"] == "add_winkelmand") 
    {
        //
        //  gebruiker heeft de toets annuleren gebruikt
        //
        if (isset($_POST["annuleer"]))
        {
            header("location: ctrl_pizzashop_bestel.php");
            exit(0);
        }
       //  
       // Alles ok, extra's en aantal pizza's is bevestigd
       // 
       // We schrijven de bestelling volledig weg
       //    $_SESSION ["best_id"] = Id van de bestellingsheader 
       //    $_SESSION ["best_dtl_id"] = Id van de bestellingsdetaillijn
       //  
       if (!isset($_SESSION["best_id"]))
       {
         // 
         // Toevoegen gegevens in bestellingsheader
         // Gebruiker doorgeven, nodig om gebruikersid te bepalen
         //
         $gebr     = GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);
         if (!isset ($_POST["Thuis_lev"] ))
            {$thuis_lev=0;}
         else 
            {$thuis_lev= $_POST["Thuis_lev"];}
         //   
         // Bijhouden oude waarde zodat deze kan getoond worden op scherm
         //
         $_SESSION ["err_bestel_aantal"] = $_POST["Aantal_pz"];
         $_SESSION ["err_thuis_lev"]     = $thuis_lev;
         $_SESSION ["err_uur_lev"]       = $_POST["Datum_uur_lev"];
         $_SESSION ["err_extra1_id"]     = $_POST ["Extra1_id"];
         $_SESSION ["err_extra2_id"]     = $_POST ["Extra2_id"];
         $_SESSION ["err_extra3_id"]     = $_POST ["Extra3_id"];
         $_SESSION ["err_extra4_id"]     = $_POST ["Extra4_id"];
         //
         try {$best_hdr = Bestelling_hdrService::create($gebr, $thuis_lev, $_POST["Datum_uur_lev"]);}
         catch (Bestelling_Uurlev_WrongException $tbe)
         {
             $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
             header("location: ctrl_pizzashop_bestel.php?action=add&error=".$error."&product_id=".$_POST ["product_id"]);
             exit(0);
         }
         //
         $_SESSION["best_id"] = $best_hdr->GetId();
         //
       }
       //
       // Toevoegen bestellingslijn
       // 
       if (isset($_POST ["product_id"])) {
            $product = ProductService::haalProductOp($_POST ["product_id"]);
            try {$best_dtl = Bestelling_dtlService::create($_SESSION["best_id"], $product, $_POST["Aantal_pz"]);}
            catch (Bestelling_Aantal_WrongException $tbe)
            {
                //
                // Errors, Aantal is niet ok
                // 
                $error = "Aantal moet tussen 0 en 100 liggen";
                header("location: ctrl_pizzashop_bestel.php?action=add&error=".$error."&product_id=".$_POST ["product_id"]);
                exit(0);
            }
            // 
            $_SESSION["best_dtl_id"] = $best_dtl->GetId();
            //
            // Toevoegen extra's van de bestellingslijn (indien nodig)
            // 
            if ($_POST ["Extra1_id"] != 0 or $_POST ["Extra2_id"] != 0 or
                    $_POST ["Extra3_id"] != 0 or $_POST ["Extra4_id"] != 0) {
                if ($_POST ["Extra1_id"] != 0) {
                    $product = ProductService::haalProductOp($_POST ["Extra1_id"]);
                    Bestelling_dtlextraService::create
                            ($best_dtl->GetId(), $product);
                }
                if ($_POST ["Extra2_id"] != 0) {
                    $product = ProductService::haalProductOp($_POST ["Extra2_id"]);
                    Bestelling_dtlextraService::create
                            ($best_dtl->GetId(), $product);
                }
                if ($_POST ["Extra3_id"] != 0) {
                    $product = ProductService::haalProductOp($_POST ["Extra3_id"]);
                    Bestelling_dtlextraService::create
                            ($best_dtl->GetId(), $product);
                }
                if ($_POST ["Extra4_id"] != 0) {
                    $product = ProductService::haalProductOp($_POST ["Extra4_id"]);
                    Bestelling_dtlextraService::create
                            ($best_dtl->GetId(), $product);
                }
            }
        }
       header("location: ctrl_pizzashop_bestel.php");
       exit(0);   
    }
    elseif ($_GET["action"] == "delete") 
    {
        print ("we gaan de lijn verwijderen, dit deel nog verder ontwikkelen");
        header("location: ctrl_pizzashop_bestel.php");
        exit(0);
    }    
    elseif ($_GET["action"] == "confirmed") {
        if (isset($_POST["Bevestigd"]))
        {
          //
          // Winkelmandje werd getoond en gebruiker heeft winkelmandje bevestigd 
          // Eventueel nog datum / uur aanpassen en al of niet thuisgeleverd
          // Sowieso wordt de status verhoogd
          //
          if (isset($_SESSION["best_id"])) 
          {
            // 
            // Gebruiker doorgeven, nodig om gebruikersid te bepalen
            //
            $status_confirmed = 1;
            //
            // Bijhouden oude waardes
            // 
            $_SESSION ["err_uur_lev"] = $_POST["Datum_uur_lev"];
            //
            // controleren of er thuislevering is voor die gebruiker en 
            //    gebruiker heeft gekozen voor thuislevering
            // in dat geval foutmelding genereren en tonen op scherm
            //
            if (!isset($_POST["Thuis_lev"])) 
            {$thuis_lev = 0;} 
            else 
            {$thuis_lev = $_POST["Thuis_lev"];}

            if ($thuis_lev == 1) 
            {
               $thuis_lev_mogelijk = GebruikerinfoService::Thuis_lev_mogelijk($_COOKIE["aangemeld"]);
               if ($thuis_lev_mogelijk == 0) 
               {
                  $error = "Thuislevering is voor u helaas niet mogelijk";
                  header("location: ctrl_pizzashop_bestel.php?error=" . $error);
                  exit(0);
               }
             }
             try {$best_hdr = Bestelling_hdrService::update($_SESSION["best_id"], $thuis_lev, $_POST["Datum_uur_lev"], $status_confirmed);} 
             catch (Bestelling_Uurlev_WrongException $tbe) 
                {
                    $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
                    header("location: ctrl_pizzashop_bestel.php?error=" . $error);
                    exit(0);
                }
              //
              header("location: ctrl_pizzashop_bestel.php");
              exit(0);
              //
            } // best_id is opgevuld
        } // toets bevestigd
        else
        {
            // 
            // Verwijder de volledige bestelling
            //
            if (isset($_SESSION["best_id"])) 
            {
               Bestelling_hdrService::delete($_SESSION["best_id"]);
               //header("location: ctrl_pizzashop_bestel.php");
               //exit(0);
            }
            
        }
    } elseif ($_GET["action"] == "show_previous") 
    {
        if (isset($_GET["error"]))
          $error = $_GET["error"];
        elseif (!isset($error))
          $error = null;
        if (isset($_COOKIE["aangemeld"])) {
            $login = "aangemeld met " . $_COOKIE["aangemeld"];
            //        
            //  check of gebruiker geblokkeerd is en niet meer mag bestellen
            //
            $actief = GebruikerinfoService::Get_Gebr_Actief($_COOKIE["aangemeld"]);
            if (isset($actief) and $actief == 0) {
                $error = "Gelieve eerst uw openstaande saldo te betalen";
            }
        } else {
            $error = "Gelieve u eerst aan te melden";
            // eerst aanmelden, + info over gekozen productid (dit onthouden voor later)
            header("location: ctrl_aanmelding.php?error=" . $error);
            exit(0);
        }
        //
        // Haal gebr_id op en geef dit door aan selectie
        //
        $gebr= GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);
        //
        $best_ovrz = Bestelling_hdrService::toonLaatsteBestelling_gebr
                      ($gebr->GetId()); 
        //
        include("presentation/pizzashop_ovrz_bestel_klant_date.php");     
        exit(0);
    }
    elseif ($_GET["action"] == "show_previous_products") 
    {
        if (isset($_COOKIE["aangemeld"])) {
            $login = "aangemeld met " . $_COOKIE["aangemeld"];
            //        
            //  check of gebruiker geblokkeerd is en niet meer mag bestellen
            //
            $actief = GebruikerinfoService::Get_Gebr_Actief($_COOKIE["aangemeld"]);
            if (isset($actief) and $actief == 0) {
                $error = "Gelieve eerst uw openstaande saldo te betalen";
            }
        } else {
            $error = "Gelieve u eerst aan te melden";
            // eerst aanmelden, + info over gekozen productid (dit onthouden voor later)
            header("location: ctrl_aanmelding.php?error=" . $error);
            exit(0);
        }
        //
        // Haal gebr_id op en geef dit door aan selectie
        //
        $gebr= GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);
        //
        $_SESSION ["err_uur_lev"] = $_POST["Datum_uur_lev"];
        //
        try {$best_ovrz = Bestelling_hdrService::toonBestelling_gebr_products
                      ($gebr->GetId(), $_POST["Datum_uur_lev"]);}
        catch (Bestelling_Uurlev_WrongException $tbe)
        {
             $error = "Datum is fout ingevoerd, formaat is dd/mm/yyyy hh:mi";
             header("location: ctrl_pizzashop_bestel.php?action=show_previous&error=".$error);
             exit(0);
        }                                                        
        //
        // $best_ovrz en $gebr worden doorgegeven
        //
        include("presentation/pizzashop_ovrz_bestel_klant_products.php");     
        exit(0);
    }
}
else 
{
    if (isset($_GET["error"]))
      $error = $_GET["error"];
    elseif (!isset($error))
      $error = null;
    // 
    // haal productinfo op zodat dit kan getoond worden op scherm
    //
    $producten        = ProductService::toonProductenCat(1); 
    $producten_drank  = ProductService::toonProductenCat(3); 
    //
    if (isset($_SESSION["best_id"]) )
    {  
      // hoofdgegevens van de bestelling  
      $best_hdr_winkelm = Bestelling_hdrService::GetById($_SESSION["best_id"]);
      // detaillijnen van de bestelling
      $best_dtl_winkelm = Bestelling_dtlService::toonBestellingen_Dtl_id($_SESSION["best_id"]);
      //
    }
    //
    if (isset($_COOKIE["aangemeld"]) )
      {$gebr            = GebruikerinfoService::haalGebrOp_Usernm($_COOKIE["aangemeld"]);}
    include("presentation/pizzashop_bestel_producten.php");     
    //    
} 
