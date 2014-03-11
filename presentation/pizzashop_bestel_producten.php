<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/normalize.css" rel="stylesheet">
<link href="css/pizza_shop.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<title>Pizzashop take me away 24 op 24, 7 op 7 voor u </title>
</head>

<body class="home">
<header>
  <div class="container">
    <h1><a href="ctrl_pizzashop_home.php" id="logo">Pizzashop Take me Away</a></h1>
    <?php 
       if (isset ($_COOKIE["aangemeld"] ))
         {$login = "aangemeld met ".$_COOKIE["aangemeld"];}
       else
         {$login = "Gelieve aan te melden om een bestelling te doen";}
       //
       if (!isset ($error))
         {$error="";}
       //
       if (!isset ($gedaan))
         {$gedaan="";}
       //
       $totbedrag = 0;
       if (isset ($gebr) )
       {
           if ($gebr->GetPostcode()->getThuis_lev_ok() == 1)
               {
                $thuis_lev = "Thuislevering mogelijk";
                if ($gebr->GetPostcode()->getKostprijs() != 0)
                  {$kostprijs = "Extra kost voor levering " . $gebr->GetPostcode()->getKostprijs();
                   $totbedrag = $totbedrag + $gebr->GetPostcode()->getKostprijs();
                  } 
                else
                  {$kostprijs = "Gratis levering ";}
               } 
           else
               {$thuis_lev = "Thuislevering is niet mogelijk, gelieve uw pizza's zelf af te halen aub";
                $kostprijs = ""; 
               }
           //
           if (isset($best_hdr_winkelm))
           {
               if ($best_hdr_winkelm->getThuis_lev()==1) 
                 {$thuis_lev_input = "checked";}
               else 
                {$thuis_lev_input = "unchecked";}
           }
       }
       else 
       {
         $thuis_lev = "";
         $kostprijs = "";
       }
       //
       if (isset($best_hdr_winkelm))
         {$wm_aanw = true;
          //
          //  Convert date/time levering
          //  
          if (isset($error))
            {$uur_lev = $_SESSION["err_uur_lev"];
             $uur_lev_show = substr($uur_lev, 0,2). "-" . substr($uur_lev, 3,2) . "-" . substr($uur_lev, 6,4) . " " . substr($uur_lev, 11,5); 
            }
          else
            {$uur_lev = $best_hdr_winkelm->getUur_lev();
             $uur_lev_show = substr($uur_lev, 8,2). "-" . substr($uur_lev, 5,2) . "-" . substr($uur_lev, 0,4) . " " . substr($uur_lev, 11,5); 
            }
          //                        
          $status = $best_hdr_winkelm->getStatus();
         }
       else 
         {$wm_aanw      = false;
          $uur_lev_show = "";
          $status       = "";
         }
    ?>  
    <br/>
    <p id="inlogok"> <?php print ($login); ?> </p>
    <p id="show_error"> <?php print ($error); ?> </p>
    <nav id="kopnav">
      <ul id="hoofdmenu">
        <li><a href="ctrl_pizzashop_home.php">Welkom</a></li>
        <li><a href="ctrl_pizzashop_bestel.php">Bestellen</a></li>
        <li><a href="ctrl_aanmelding.php">Aanmelden</a></li>
        <li><a href="ctrl_pizzashop_gastenboek.php">Gastenboek</a></li>        
      </ul>
    </nav>
  </div>
</header>
<div id="inhoud" class="container">
  <div id="main_bestel" >
      <div id="show_confirm_msg">
         <h2> <?php if ($status ==1) {print ("Bedankt voor uw bestelling, de pizza's worden voor u klaargemaakt");} ?> </h2>
      </div>
      <div class="winkelmandje">
      <?php if ($status == 0) { ?>
      <h2> Winkelmandje </h2>
      <table>
          <?php
             if ($wm_aanw)
             {
          ?>   
          <tr> 
              <th>Aantal</th> 
              <th>Naam</th> 
              <th>Prijs pizza</th> 
              <th>Extra's</th> 
              <th>Totaal</th> 
          </tr> 
          <?php } ?>
          <?php
           if ($wm_aanw)
           {
              $lijst_extra_lijnen = array();
              foreach ($best_dtl_winkelm as $best_dtl) 
              {
                  $totbedrag = $totbedrag + 
                        ($best_dtl->getAantal() *  ($best_dtl->getPrijs() - $best_dtl->getKorting() ));
          ?>
          <tr>
             <td> 
                <?php print($best_dtl->getAantal() ); ?>   
             </td>
             <td> 
                <?php print($best_dtl->getProduct()->GetNaam() ); ?>   
             </td>
             <td> 
                <?php print($best_dtl->getPrijs() - $best_dtl->getKorting() ); ?>   
             </td>
            
             <td class="italic">
                <?php
                   //
                   $lijst_extra_lijnen = "";                   
                   $lijst_extra_lijnen = $best_dtl->getBestel_dtl_extra();
                   //
                   $totextra = 0;
                   foreach ($lijst_extra_lijnen as $best_dtl_extra) 
                     { 
                       $totbedrag = $totbedrag + 
                         ( $best_dtl_extra->getPrijs()- $best_dtl_extra->getKorting());
                       $totextra  = $totextra + ( $best_dtl_extra->getPrijs()- $best_dtl_extra->getKorting());
                       
                       print ("+ " . $best_dtl_extra->getProduct()->GetNaam() . " = " . ( $best_dtl_extra->getPrijs()- $best_dtl_extra->getKorting()) );     
                     }
                 ?>   
             </td>    
             <td> 
                <?php print($totextra + ($best_dtl->getAantal() * ($best_dtl->getPrijs() - $best_dtl->getKorting()) )); ?>
             </td>
             <td> 
                <img src="images/rsz_prullemand.jpg"></img>
  <!--              <a href="ctrl_pizzashop_bestel.php?action=delete&line_id= <?php print($best_dtl->getId()); ?>"><img src="images/rsz_prullemand.jpg"></a>    -->
             </td>
             
          </tr>
          <?php 
              }
          ?>
      </table>
      <p class="labels_winkelm"> 
      <?php
         print ($kostprijs . " Euro" . "<br>");
         print ("Totaal bedrag = ".$totbedrag . " Euro");}
      ?>
      </p>
      <?php 
         if ($wm_aanw)
         {
      ?>  
      <h3> Bevestig uw bestelling </h3>
      <?php } else {print ("Uw winkelmandje is leeg");} ?>
      <form id="bevestig_winkelm" method="post" action="ctrl_pizzashop_bestel.php?action=confirmed"> 
        <table id="show_winkelmandje"> 
          <?php
           if (isset($best_hdr_winkelm))
           {
              ?> 
              <tr> 
                  <td> Datum en uur levering </td>
                  <td> 
                    <input type="datetime" name="Datum_uur_lev" pattern="\d{1,2}-\d{1,2}-\d{4} \d{1,2}:\d{1,2}" value="<?php print($uur_lev_show); ?>" required>   
                  </td>
              </tr>
              <tr>
                  <td> 
                      <?php print($thuis_lev); ?> 
                  </td> 
              </tr>
              <tr>
                  <td> 
                      <?php if ($gebr->GetPostcode()->getThuis_lev_ok() == 1)
                              {print ("Thuis afleveren ? ");
                      ?>
                  </td> 
                  <td>
                      <input type="checkbox" name="Thuis_lev" value="1" <?php print ($thuis_lev_input); ?> >
                  </td>                  
                     <?php } else {?>
                  </td> 
                  <td>
                      <input type="hidden" name="Thuis_lev" value="0">
                  </td>   
                  <?php } ?>
              </tr>
              <tr>
                <td><input type="submit" value="Bevestig uw winkelmandje" name="Bevestigd"></td>
                <td><input type="submit" value="Verwijder alles" name="Annuleer"></td>
              </tr>
              <?php
           }
          ?> 
        </table>
      </form>
      <?php } ?>
      </div>
      
      <h2> Laatste bestelling </h2>
      <form id="toon_vorige_bestelling" method="post" action="ctrl_pizzashop_bestel.php?action=show_previous"> 
         <input type="submit" value="Toon laatste bestellingen">         
      </form>          
      <h2> Pizza's </h2>
      <table id="show_productinfo"> 
          <tr> 
              <th>Naam</th> 
              <th>Prijs</th> 
              <th>Korting</th> 
              <th>Netto</th> 
          </tr> 
          <?php
          foreach ($producten as $prod) {
              ?> 
              <tr> 
                  <td id="product_naam"> 
                      <a href="ctrl_pizzashop_bestel.php?action=show_product&product_id=<?php print($prod->getId()); ?>"> 
                          <?php print($prod->getNaam()); ?> 
                      </a>          
                  </td> 
                  <td> 
                      <?php print($prod->getPrijs() ); ?> 
                  </td> 
                  <td> 
                      <?php print($prod->getKorting()); ?> 
                  </td>
                  <td> 
                      <?php print($prod->getPrijs() - $prod->getKorting() ); ?> 
                  </td> 
                  <td>
                      <a href="ctrl_pizzashop_bestel.php?action=add&product_id= <?php print($prod->getId()); ?>"><img src="images/rsz_winkelm_klein.jpg"/></a>   
                  </td>
              </tr> 
              <?php
          }
          ?> 
      </table> 
      <h2> Extra </h2>
      <table id="show_productinfo"> 
          <tr> 
              <th>Naam</th> 
              <th>Prijs</th> 
              <th>Korting</th> 
              <th>Netto</th> 
          </tr> 
          <?php
          foreach ($producten_drank as $prod) {
              ?> 
              <tr> 
                  <td id="product_naam"> 
                      <a href="ctrl_pizzashop_bestel.php?action=show_product&id=<?php print($prod->getId()); ?>"> 
                          <?php print($prod->getNaam()); ?> 
                      </a>          
                  </td> 
                  <td> 
                      <?php print($prod->getPrijs() ); ?> 
                  </td> 
                  <td> 
                      <?php print($prod->getKorting()); ?> 
                  </td>
                  <td> 
                      <?php print($prod->getPrijs() - $prod->getKorting() ); ?> 
                  </td> 
                  <td>
                      <a href="ctrl_pizzashop_bestel.php?action=add&product_id= <?php print($prod->getId()); ?>"><img src="images/rsz_winkelm_klein.jpg"/></a>   
                  </td>
              </tr> 
              <?php
          }
          ?> 
      </table> 
  </div>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx</div>
</footer>
</body>
</html>
