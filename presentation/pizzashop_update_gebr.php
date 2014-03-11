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
       if (isset ($error))
       {
            $klant_naam        = $_SESSION ["err_klant_naam"];
            $klant_level_auth  = $_SESSION ["err_klant_level_auth"];
            $klant_adres       = $_SESSION ["err_klant_adres"]     ;
            $klant_email       = $_SESSION ["err_klant_email"]     ;
            $klant_telefoon    = $_SESSION ["err_klant_telefoon"]  ;
            $klant_actief      = $_SESSION ["err_klant_actief"]    ;
            $klant_postcode_id = $_SESSION ["err_klant_postcode_id"];
            $klant_korting     = $_SESSION ["err_klant_korting"]   ;
            $klant_opm_extra   = $_SESSION ["err_klant_opm_extra"] ;       
       }
       else 
       {    $error="";
            $klant_naam        = $gebr->getNaam();
            $klant_level_auth  = $gebr->getLevel_auth();
            $klant_adres       = $gebr->getAdres();
            $klant_email       = $gebr->getEmail();
            $klant_telefoon    = $gebr->getTelefoon();
            $klant_actief      = $gebr->getActief();
            $klant_postcode_id = $gebr->getPostcode_id();
            $klant_korting     = $gebr->getKorting();
            $klant_opm_extra   = $gebr->getOpm_extra();
       }
       
    ?>  
    <br/>
    <p id="inlogok"> <?php print ($login); ?> </p>
    <p id="show_error"> <?php print ($error); ?> </p>
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
  <section id="main" >
      <h3> Usernaam = <?php print($gebr->getUsernm()); ?> </h3>
      <div class="kolom">
        <form id="inv_productinfo" method="post" action="ctrl_gebruikers.php?action=update&id=<?php print($gebr->getId()); ?>">  
           <table>
              <tbody>    
                  <tr>
                      <td>Naam</td>
                      <td> 
                        <input type="text" name="Naam" required value="<?php print($klant_naam) ?>">
                      </td>
                  </tr> 
                  <tr>
                      <td>Level</td>
                      <td> 
                        <input type="number" min="0" max="1" name="Level_auth" required value="<?php print($klant_level_auth) ?>">
                      </td>
                  </tr>          
                  <tr>
                      <td>Adres</td>
                      <td> 
                        <input type="text" name="Adres" required value="<?php print($klant_adres) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Plaats</td>
                      <td><select name="Postcode_id" required>
                          <?php
                            foreach($postcodes as $pc) 
                             {
                              //if ($pc['id'] == $gebr->getPostcode_Id())
                              if ($pc['id'] == $klant_postcode_id)  
                                $selected=" selected = 'selected'";
                              else
                                $selected="";  
                              print ("<option value=" . $pc['id']. $selected . '>' .  $pc['gemeente'] . " (" . $pc['postnr'] . ")" .  '</option>');                                
                             }
                          ?>
			</select>                  
                      </td>      
                  </tr>
                  <tr>
                      <td>Email</td>
                      <td> 
                        <input type="email" name="Email" required value="<?php print($klant_email) ?>">
                      </td>
                  </tr>     
                  <tr>
                      <td>Telefoon</td>
                      <td> 
                        <input type="text" name="Telefoon" value="<?php print($klant_telefoon) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Actief</td>
                      <td> 
                        <input type="number" name="Actief" value="<?php print($klant_actief) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Korting</td>
                      <td> 
                        <input type="number" name="Korting" value="<?php print($klant_korting) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Extra</td>
                      <td> 
                        <input type="text" name="Opm_extra" value="<?php print($klant_opm_extra) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td></td>
                      <td><input type="submit" value="Bewaren"></td>
                  </tr>
              </tbody>
           </table>
        </form>
      </div>
      
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx</div>
</footer>
</body>
</html>
