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

<title>Pizzashop take me away 24 op 24, 7 op 7 voor u</title>
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
      if ($error == "gebr_exists") 
      {$show_error = "Gebruikersnaam bestaat al !";}
      else
      {$show_error = "";}
    ?>      
    <br/>
    <p id="inlogok"> <?php print ($login); ?> </p>
    <p id="show_error"> <?php print ($show_error); ?> </p>
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
  <section id="main">
      <div class="kaart_rechts">
         <p>
              Thuislevering gratis in paarse gebied, errond is betalend, buiten blauwe lijn geen thuislevering
         </p> 
         <p>
           <img src="images/kaart2_pizzashop_napoli.jpg">
         </p> 
      </div>
      <form method="post" action="ctrl_voeg_gebr_toe.php?action=process"> 
          <table>
              <tbody>
                  <tr>
                      <td>Gebruikersnaam</td>
                      <td><input type="text" name="usernm" required></td>
                  </tr>
                  <tr>
                      <td>Wachtwoord</td>
                      <td><input type="password" name="pasw" required></td>
                  </tr>
                  <tr>
                      <td>Naam</td>
                      <td><input type="text" name="naam"  required></td>
                  </tr>
                  <tr>
                      <td>Adres</td>
                      <td><input type="text" name="adres"  required></td>
                  </tr>
                  <tr>
                      <td>Woonplaats</td>
                      <td><select name="postcode_id"  required>
                          <?php
                            foreach($postcodes as $pc) 
                             {
                              if ($pc['id'] == 1)
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
                      <td><input type="email" name="email"></td>
                  </tr>
                  <tr>
                      <td>Telefoon</td>
                      <td><input type="text" name="telefoon" required></td>
                  </tr>
                  <tr>
                      <td>Opmerkingen</td>
                      <td><input type="textarea" name="opm_extra"></td>
                  </tr>
                  <tr>
                      <td>Btwnr</td>
                      <td><input type="text" name="btwnr"></td>
                  </tr>
                  <tr>
                      <td></td>
                      <td><input type="submit" value="Registreren"></td>
                  </tr>
              </tbody>
          </table>
      </form>     
      
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx </div>
</footer>
</body>
</html>
