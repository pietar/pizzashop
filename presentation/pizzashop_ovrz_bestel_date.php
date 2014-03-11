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
    ?>  
    <br/>
    <nav id="kopnav">
      <ul id="hoofdmenu">
        <li><a href="ctrl_pizzashop_home.php">Home</a></li>
        <li><a href="ctrl_ovrz_bestellingen.php">Bestellingen</a></li>
        <li><a href="ctrl_pizzashop_bd_info.php">Bedrijfsinfo</a></li>
        <li><a href="ctrl_product.php">Productinfo</a></li>
        <li><a href="ctrl_gebruikers.php">Klanteninfo</a></li>
        <li><a href="ctrl_plaatsen.php">Gemeentes</a></li>
      </ul>
    </nav>
  </div>
</header>
<div id="inhoud" class="container">
  <section id="main_bestel" >
      <h2> Overzicht bestellingen, gesorteerd per datum, uur en gemeente </h2>
      <table>
          <?php
             $best_hdr_id = 0;
             $tot_alle_bestellingen = 0;
             $totbedrag = 0;
             foreach ($best_ovrz as $best) 
             {
                 // check and reset en totalen resetten
                 if (get_class($best) == "Bestelling_hdr")
                 {
                     if ($best_hdr_id != $best->GetId() )
                     {
                         //
                         if ($best_hdr_id !=0) 
                           {print ("<br>"."Totaal bedrag = ".$totbedrag);}
                         //                         
                         $best_hdr_id = $best->GetId();
                         $tot_alle_bestellingen = $tot_alle_bestellingen + $totbedrag;
                         $totbedrag   = 0;
                     }
                 }
                 //
                 switch (get_class ($best)) 
                 {
                     case "Bestelling_hdr": 
                         {
          ?>
          <tr>
            <td>
                <?php print ("Datum en uur : " . $best->GetUur_lev()); ?>
            </td>
            <td>
                <?php print ($best->GetGebr()->GetNaam()); ?>
            </td>
            <td>
                <?php print ($best->GetGebr()->GetPostcode()->GetGemeente()); ?>
            </td>
            <td>
                <?php print ($best->GetGebr()->GetAdres()); ?>
            </td>
            <td>
                <?php print ($best->GetGebr()->GetTelefoon()); 
                      $totbedrag = $totbedrag + $best->GetGebr()->GetPostcode()->GetKostprijs();
                      break;
                }?>
            </td>
          </tr>
          <tr>
          </tr>
          <?php
                     case "Bestelling_dtl": 
                     {
          ?>
          <tr>
            <td>
            </td>
            <td>
                <?php print ($best->getAantal() . "*". $best->getProduct()->GetNaam() ); ?>
            </td>
            <td>
                <?php print (" prijs = " . $best->GetAantal() * ($best->GetPrijs() - $best->GetKorting()) ); 
                      $totbedrag = $totbedrag + $best->GetAantal() * ($best->GetPrijs() - $best->GetKorting());
                      break;
                }?>
            </td>
          </tr>
          <?php
                     case "Bestelling_dtl_extra": 
                     {
          ?>
          <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
                <?php print ("Extra op pizza = ". $best->getProduct()->GetNaam(). " Prijs = " . ($best->GetPrijs() - $best->GetKorting()) ); 
                      $totbedrag = $totbedrag + ($best->GetPrijs() - $best->GetKorting());
                      break;
                }?>
            </td>
          </tr>
          <?php
             }
             }
             // Laatste totaal ook nog maken
             //
             if ($totbedrag !=0)
               {print ("<br>"."Totaal bedrag = ".$totbedrag);
                $tot_alle_bestellingen = $tot_alle_bestellingen + $totbedrag;
               }
             if ($tot_alle_bestellingen !=0 )
               {print ("<br>"."Totaal alle bestellingen = ".$tot_alle_bestellingen);}

          ?>
          
      </table>
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx</div>
</footer>
</body>
</html>
