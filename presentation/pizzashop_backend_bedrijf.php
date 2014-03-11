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
       {
         $login = "aangemeld met ".$_COOKIE["aangemeld"];  
       }
       else
       {
         $login = "Gelieve aan te melden om een bestelling te doen";  
       }
    ?>  
    <br/>
    <p id="inlogok"> <?php print ($login); ?> </p>
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
      <?php 
        foreach ($bedrijfsinfo as $bd_info) { 
      ?> 
      <div class="kolom">
        <form id="inv_bedrijfsinfo" method="post" action="ctrl_pizzashop_bd_info.php?action=process">             
           <table>
              <tbody>    
                  <tr>
                      <td>Bedrijfsnaam</td>
                      <td> 
                        <input type="text" name="Naam" required value="<?php print($bd_info->getNaam()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Adres</td>
                      <td> 
                        <input type="text" name="Adres" required value="<?php print($bd_info->getAdres()) ?>">
                      </td>
                  </tr>          
                  <tr>
                      <td>Plaats</td>
                      <td><select name="Postcode_id">
                          <?php
                            foreach($postcodes as $pc) 
                             {
                              if ($pc['id'] == $bd_info->getPostcode_Id())
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
                      <td>Telefoon</td>
                      <td> 
                        <input type="text" name="Telefoon" required value="<?php print($bd_info->getTelefoon()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Gsm</td>
                      <td> 
                        <input type="text" name="Gsm" value="<?php print($bd_info->getGsm()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Email</td>
                      <td> 
                        <input type="email" name="Email" required value="<?php print($bd_info->getEmail()) ?>">
                      </td>
                  </tr>     
                  <tr>
                      <td>Openingsuren</td>
                      <td> 
                        <input type="text" name="Openinguren" value="<?php print($bd_info->getOpeninguren()) ?>">
                      </td>
                  </tr>                  
                  
                  <tr>
                      <td>Algemene info</td>
                      <td> 
                        <input type="text" name="Alg_info" value="<?php print($bd_info->getAlg_info()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Promotie</td>
                      <td> 
                        <input type="text" name="Promotie" value="<?php print($bd_info->getPromotie()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Leveringsvoorwaarden</td>
                      <td> 
                        <input type="text" name="Lev_vw" value="<?php print($bd_info->getLev_vw()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Faq</td>
                      <td> 
                        <input type="text" name="Faq_info" value="<?php print($bd_info->getFaq_info()) ?>">
                      </td>
                  </tr>        
                  <tr>
                      <td> 
                        <input type="hidden" name="Id" value="<?php print($bd_info->getId()) ?>">
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
      <?php 
        } 
      ?> 
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx, 
                           nog niet op html5/css3 letten aub </div>
</footer>
</body>
</html>
