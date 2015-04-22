<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 4-11-2014
 * Time: 13:29
 */

//Als submit is gedrukt dan
if (isset($_POST['bes_submit'])){
    //als de gebruiker NIET is ingelogd dan
    if(!isset($_SESSION['soortgebruiker']) || $_SESSION['soortgebruiker'] != 'klant' ){
        //mocht er een sessie zijn gezet; verdwijder deze
        if (isset($_SESSION['bes'])) {unset($_SESSION['bes']);}
        //doorsturen naar index met foutmelding
        header ('location: index.php?res=nlog');
    } else {
        //bestelling toevoegen aan sessie in associatieve array "bes" formaat = ArtikelID => Besteld Aantal
        $_SESSION['bes'][$_GET['id']] = $_POST['bes_aantal'];
        //Doorsturen naar index met melding dat het gelukt is.
        header('location: index.php?res=success');
    }
}