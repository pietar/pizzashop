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
       if (!isset ($error))
         {$error= null;}
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
      
      <div class="kolom">
        <form id="inv_productinfo" method="post" action="ctrl_plaatsen.php?action=update&id=<?php print($pc->getId()); ?>">  
           <table>
              <tbody>    
                  <tr>
                      <td>Postnr</td>
                      <td> 
                        <input type="number" name="Postnr" required value="<?php print($pc->getPostnr()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Gemeente</td>
                      <td> 
                        <input type="text" name="Gemeente" required value="<?php print($pc->getGemeente()) ?>">
                      </td>
                  </tr>          
                  <tr>
                      <td>Kostprijs</td>
                      <td> 
                        <input type="number" name="Kostprijs" required value="<?php print($pc->getKostprijs()) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Thuislevering</td>
                      <td> 
                        <input type="number" name="Thuis_lev_ok" value="<?php print($pc->getThuis_lev_ok()) ?>">
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
