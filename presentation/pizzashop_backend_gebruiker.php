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
      <table id="show_productinfo"> 
          <tr> 
              <th>Naam</th> 
              <th>Usernaam</th> 
              <th>Level</th> 
              <th>Adres</th> 
              <th>Plaats</th> 
              <th>Email</th> 
              <th>Telefoon</th> 
              <th>Actief</th> 
              <th>Korting</th> 
              <th>Extra</th> 
          </tr> 
          <?php
          foreach ($gebruikers as $gebr) {
              ?> 
              <tr> 
                  <td> 
                      <a href="ctrl_gebruikers.php?action=change&id=<?php print($gebr->getId()); ?>"> 
                          <?php print($gebr->getNaam()); ?> 
                      </a>          
                  </td> 
                  <td> 
                      <?php print($gebr->getUsernm()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getLevel_auth()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getAdres()); ?> 
                  </td>
                  <td> 
                      <?php print($gebr->getPostcode()->getGemeente()); ?> 
                  </td>
                  <td> 
                      <?php print($gebr->getEmail()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getTelefoon()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getActief()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getKorting()); ?> 
                  </td> 
                  <td> 
                      <?php print($gebr->getOpm_extra()); ?> 
                  </td> 
                  <!-- <td> -->
                  <!--    <a href="ctrl_gebruikers.php?action=delete&id= <?php print($gebr->getId()); ?>">  Verwijder</a>    -->
                  <!-- </td> -->
              </tr> 
              <?php
          }
          ?> 
      </table> 
      <div class="prod_toev">
         <form method="post" action="ctrl_gebruikers.php?action=new">               
              <td><input type="submit" value="Nieuwe gebruiker"></td>
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
