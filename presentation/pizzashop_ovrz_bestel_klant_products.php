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
      <h2> Totalen bestellingen gesorteerd per pizza </h2>
      <table>
          <tr> 
              <th>Naam</th> 
              <th>Aantal</th> 
          </tr>
          <?php
             $totaal = 0;
             foreach ($best_ovrz as $best) 
             {
          ?>
          <tr>
            <td>
                <?php print ($best->getProduct()->GetNaam()) ?>
            </td>
            <td>
                <?php print ($best->getAantal()  ); 
                      $totaal = $totaal + $best->getAantal();
                }?>
            </td>
          </tr>
          <tr> <td></td><td>----</td></tr>
          <tr>
              <td> Totaal = 
              </td>
              <td>
                <?php print ($totaal);?>
              </td>
          </tr>
          
      </table>
      <form method="post" action="ctrl_pizzashop_bestel.php?action=show_previous"> 
          <input type="submit" value="Per leveringsdatum">
      </form>
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx</div>
</footer>
</body>
</html>
