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
           $login = "";  
       }
      if ($error == "gebr_pasw_niet_ok") 
        {$show_error = "Uw gebruikersnaam of uw paswoord klopt niet !";}
      elseif ($error == "gebr_actief_niet_ok")
        {$show_error = "U kan zich niet aanmelden, gelieve eerst uw openstaande saldo te betalen ";}
      else
        {$show_error = $error;      
        }
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
      <form method="post" action="ctrl_aanmelding.php?action=process"> 
          <table>
              <tbody>
                  <br>
                  <tr>
                      <td>Gebruikersnaam</td>
                      <td><input type="text" name="usernm" required></td>
                  </tr>
                  <tr>
                      <td>Wachtwoord</td>
                      <td><input type="password" name="pasw" required></td>
                  </tr>
                  <tr>
                      <td></td>
                      <td><input type="submit" value="Aanmelden"></td>
                  </tr>
              </tbody>
          </table>
      </form>          
      <form method="post" action="ctrl_voeg_gebr_toe.php" id ="button_nieuwe_gebr"> 
         <p> Bent u een nieuwe gebruiker ?  </p>
         <input type="submit" value="Nieuwe gebruiker">
      </form>        
      <br>
  </section>
  <!--einde inhoud--> 
</div>
<footer>
  <div class="container"> Deze site is gemaakt als eindtest PHP door Piet Arickx </div>
</footer>
</body>
</html>
