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
    if (isset($_COOKIE["aangemeld"])) {
        $login = "aangemeld met " . $_COOKIE["aangemeld"];
    } else {
        $login = "Gelieve aan te melden om een bestelling te doen";
    }
    if (!isset($error)) 
      {$error = "";}
    if (!isset ($_SESSION ["err_date1"]))
     {
       $uur_lev1 = date('d-m-Y H:i');
       $uur_lev2 = date('d-m-Y H:i');
      }
    else 
    {  
       $uur_lev1 = $_SESSION ["err_date1"];
       $uur_lev2 = $_SESSION ["err_date2"];
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
  <section id="main_bestel" >
      <br>
      <h2> Overzicht bestellingen </h2>
      <h4> Selecties </h4>
      <form id="inv_productinfo_extra" method="post" action="ctrl_ovrz_bestellingen.php?action=toon_ovrz" >  
          <p class="labels_blue" id="keuze_lijst"> Uw keuze overzicht (momenteel maar de eerste beschikbaar !): </p>
          <Input required type = 'Radio' Name ='Keuze_lijst' checked="checked" value= '1' 1>Overzicht planning, gesorteerd per uur, gemeente <br>
          <Input required type = 'Radio' Name ='Keuze_lijst' value= '2' 2>Overzicht bestellingen per klant <br>
          <Input required type = 'Radio' Name ='Keuze_lijst' value= '3' 3>Overzicht bestellingen per pizza <br>
          <br>
          <table>
              <tr>
                  <td class="labels_blue">Tussen datum en uur levering : </td>
                  <td> 
                    <input type="datetime" name="Datum_uur_lev1" pattern="\d{1,2}-\d{1,2}-\d{4} \d{1,2}:\d{1,2}" value="<?php print ($uur_lev1); ?>" required> 
                  </td>
                  <td class="labels_blue"> en : </td>
                  <td> 
                     <input type="datetime" name="Datum_uur_lev2" pattern="\d{1,2}-\d{1,2}-\d{4} \d{1,2}:\d{1,2}" value="<?php print ($uur_lev2); ?>" required> 
                  </td>
              </tr>    
              <tr>
                  <td></td>
                  <td><input type="submit" value="Toon overzicht"></td>
              </tr>                  
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
