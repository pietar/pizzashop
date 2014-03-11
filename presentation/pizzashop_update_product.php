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
       // indien er een foutmelding gekomen is, best deze waardes terug tonen op scherm ipv 
       //    originele waardes
       //
       if (isset($error))
       {
           $prod_id           = $_SESSION ["err_product_id"];
           $prod_naam         = $_SESSION ["err_product_naam"];
           $prod_prijs        = $_SESSION ["err_product_prijs"];
           $prod_korting      = $_SESSION ["err_product_korting"];
           $prod_categorie    = $_SESSION ["err_product_categorie"];
           $prod_status       = $_SESSION ["err_product_status"];
           $prod_beschrijving = $_SESSION ["err_product_beschrijving"];
       }
       else 
       {
           $prod_id           = $product->getId();
           $prod_naam         = $product->getNaam();
           $prod_prijs        = $product->getPrijs();
           $prod_korting      = $product->getKorting();
           $prod_categorie    = $product->getCategorie();
           $prod_status       = $product->getStatus();
           $prod_beschrijving = $product->getBeschrijving();
           $error="";
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
      
      <div class="kolom">
        <form id="inv_productinfo" method="post" action="ctrl_product.php?action=update&id=<?php print($product->getId()); ?>">  
           <table>
              <tbody>    
                  <tr>
                      <td>Naam</td>
                      <td> 
                        <input type="text" name="Naam" required value="<?php print($prod_naam) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Beschrijving</td>
                      <td> 
                        <input type="text" name="Beschrijving" required value="<?php print($prod_beschrijving) ?>">
                      </td>
                  </tr>          
                  <tr>
                      <td>Prijs</td>
                      <td> 
                        <input type="number" name="Prijs" required value="<?php print($prod_prijs) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Korting</td>
                      <td> 
                        <input type="number" name="Korting" value="<?php print($prod_korting) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td>Categorie</td>
                      <td> 
                        <input type="number" name="Categorie" required value="<?php print($prod_categorie) ?>">
                      </td>
                  </tr>     
                  <tr>
                      <td>Status</td>
                      <td> 
                        <input type="number" name="Status" value="<?php print($prod_status) ?>">
                      </td>
                  </tr>                  
                  <tr>
                      <td> 
                        <input type="hidden" name="Id" value="<?php print($product->getId()) ?>">
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
