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
    if (isset($_COOKIE["aangemeld"])) 
      {$login = "aangemeld met " . $_COOKIE["aangemeld"];} 
    else 
      {$login = "Gelieve aan te melden om een bestelling te doen";}
      
    if (isset($error)) 
      {$bestel_aantal = $_SESSION ["err_bestel_aantal"];
       $uur_lev       = $_SESSION ["err_uur_lev"];
       $extra1_id     = $_SESSION ["err_extra1_id"];
       $extra2_id     = $_SESSION ["err_extra2_id"];     
       $extra3_id     = $_SESSION ["err_extra3_id"];     
       $extra4_id     = $_SESSION ["err_extra4_id"]; 
       if ($_SESSION ["err_thuis_lev"] == 1)
         {$thuis_lev_input = "checked";}
       else 
         {$thuis_lev_input = "unchecked";}
      }
    else
      {$error = "";
       $bestel_aantal = 1; 
       $uur_lev       = date('d-m-Y H:i');
       $extra1_id     = null;
       $extra2_id     = null;
       $extra3_id     = null;
       $extra4_id     = null;
       if ($gebr->GetPostcode()->getThuis_lev_ok() == 1)
       {$thuis_lev_input = "checked";}
       else
       {$thuis_lev_input = "unchecked";}
      }
    //
    if ($gebr->GetPostcode()->getThuis_lev_ok() == 1) {
        $thuis_lev       = "Thuislevering is mogelijk";
        //$thuis_lev_input = "checked";
        //
        if ($gebr->GetPostcode()->getKostprijs() != 0) {
            $kostprijs = "Extra kost voor levering " . $gebr->GetPostcode()->getKostprijs();
        } else {
            $kostprijs = "Gratis levering ";
        }
    } else {
        $thuis_lev       = "Thuislevering is niet mogelijk, gelieve uw pizza's zelf af te halen aub";
        $kostprijs       = "";
        //$thuis_lev_input = "unchecked";
    }
    // product_id = pizza die gekozen werd 
    $product_id = $_GET["product_id"];
    //
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
  <section id="main_bestel" >
      <br>
      <h2> Gekozen pizza </h2>
      <table id="show_productinfo"> 
          <tr> 
              <th>Naam</th> 
              <th>Beschrijving</th> 
              <th>Netto-prijs</th> 
          </tr> 
              <tr> 
                  <td id="product_naam"> 
                      <?php print($prod->getNaam()); ?> 
                  </td> 
                  <td> 
                      <?php print($prod->getBeschrijving() ); ?> 
                  </td> 
                  <td> 
                      <?php print($prod->getPrijs() - $prod->getKorting() ); ?> 
                  </td> 
              </tr> 
      </table> 
      <br>
      <form id="inv_productinfo_extra" method="post" action="ctrl_pizzashop_bestel.php?action=add_winkelmand" >  
           <table>
             <tr>
               <td id="label_pizza"> Aantal : </td>
               <td> 
                  <input id="aantal_pz" type="number" name="Aantal_pz" required value=<?php print ($bestel_aantal); ?>>
               </td>
             </tr>    
             <?php if (!isset($_SESSION["best_id"])) { ?> 
             <tr>
               <td id="datum_uur_lev"> Datum en uur levering : </td>
               <td> 
                  <input type="datetime" name="Datum_uur_lev" pattern="\d{1,2}-\d{1,2}-\d{4} \d{1,2}:\d{1,2}" value="<?php print ($uur_lev); ?>" required> 
               </td>
               <td> 
                  <?php print ($thuis_lev. " -- " . $kostprijs ); ?>
               </td>
             </tr>    
             <tr>
                <?php if ($gebr->GetPostcode()->getThuis_lev_ok() == 1) { ?>
                  <td id="thuis_lev"> Thuislevering ? </td>
                <?php }?>
               <td> 
                  <?php if ($gebr->GetPostcode()->getThuis_lev_ok() == 1) { ?>
                  <input type="checkbox" name="Thuis_lev" value="1" <?php print ($thuis_lev_input); ?> >
                  <?php } else { ?>
                  <input type="hidden" name="Thuis_lev" value="0">
                  <?php } ?>
               </td>
             </tr>       
             <?php }?>
           </table>
           <h2> Extra's op je pizza  </h2>
           <table>
              <tbody>    
                  <tr>
                     <td>1 : </td>
                      <td><select name="Extra1_id">
                              <?php
                            print ("<option value=0> ------- geen ------------ </option>");                                                          
                            foreach($producten_extra as $prod_extra) 
                             {
                              if ($prod_extra->Getid() == $extra1_id)
                                $selected=" selected = 'selected'";
                              else
                                $selected="";  
                              print ("<option value=" . $prod_extra->Getid() . $selected . '>' .  $prod_extra->GetNaam() . " (prijs = " . $prod_extra->GetPrijs() . " Euro)" .  '</option>');                                
                             }
                             ?>
			</select>                  
                      </td>                            
                  </tr>                  
                  <tr>
                      <td>2 : </td>
                      <td><select name="Extra2_id">
                         <?php
                            print ("<option value=0> ------- geen ------------ </option>");                                                          
                            foreach($producten_extra as $prod_extra) 
                             {
                              if ($prod_extra->Getid() == $extra2_id)
                                $selected=" selected = 'selected'";
                              else
                                $selected="";  
                              print ("<option value=" . $prod_extra->Getid() . $selected . '>' .  $prod_extra->GetNaam() . " (prijs = " . $prod_extra->GetPrijs() . " Euro)" .  '</option>');                                
                             }
                         ?>
			</select>                  
                      </td>                            
                  </tr>          
                  <tr>
                      <td>3 : </td>
                      <td><select name="Extra3_id">
                          <?php
                            print ("<option value=0> ------- geen ------------ </option>");                                                          
                            foreach($producten_extra as $prod_extra) 
                             {
                              if ($prod_extra->Getid() == $extra3_id)
                                $selected=" selected = 'selected'";
                              else
                                $selected="";  
                              print ("<option value=" . $prod_extra->Getid() . $selected . '>' .  $prod_extra->GetNaam() . " (prijs = " . $prod_extra->GetPrijs() . " Euro)" .  '</option>');                                
                             }
                          ?>
			</select>                  
                      </td>                            
                  </tr>    
                  <tr>
                      <td>4 : </td>
                      <td><select name="Extra4_id">
                          <?php
                            print ("<option value=0> ------- geen ------------ </option>");                                                          
                            foreach($producten_extra as $prod_extra) 
                             {
                              if ($prod_extra->Getid() == $extra4_id)
                                $selected=" selected = 'selected'";
                              else
                                $selected="";  
                              print ("<option value=" . $prod_extra->Getid() . $selected . '>' .  $prod_extra->GetNaam() . " (prijs = " . $prod_extra->GetPrijs() . " Euro)" .  '</option>');                                
                             }
                          ?>
			</select>                  
                      </td>                            
                  </tr>                      
                  <tr>
                      <td></td>
                      <td><input type="submit" value="Toevoegen aan bestelling" name="voegtoe"></td>
                      <td></td>
                      <td><input type="submit" value="Annuleer gekozen pizza" name="annuleer"></td>
                     <td> 
                       <input type="hidden" name="product_id" value="<?php print($product_id); ?>">
                     </td>                      
                  </tr>
              </tbody>
              
                  
           </table>
        </form>
      
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx</div>
</footer>
</body>
</html>
