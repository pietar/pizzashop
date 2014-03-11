
<?php 
    require_once("business/bedrijfsinfoservice.class.php"); 
    $bedrijfsinfo = BedrijfsinfoService::toonBedrijfInfo(); 
    include("presentation/pizzashop_home.php"); 
?>

