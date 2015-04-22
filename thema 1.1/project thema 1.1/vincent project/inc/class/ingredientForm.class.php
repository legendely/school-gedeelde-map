<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 31-10-2014
 * Time: 22:54
 */

//initializeer id
if (isset($_POST['ing_id'])){
    //als id van een post is gezet
    $sqid = mysqli_real_escape_string($con, $_POST['ing_id']);
} else if (isset($_GET['id'])){
    //als een id van get is gezet
    $sqid = mysqli_real_escape_string($con, $_GET['id']);
} else {
    //als er geen id is gezet
    $sqid = "";
}

//prepareer alle variabelen voor sql als submit is ingedrukt
if (isset($_POST['ing_submit'])) {
    $sqnaam = isset($_POST['ing_naam']) ? mysqli_real_escape_string($con, $_POST['ing_naam']) : "";
    $sqtv = isset($_POST['ing_tv']) ? mysqli_real_escape_string($con, $_POST['ing_tv']) : "";
    $sqib = isset($_POST['ing_ib']) ? mysqli_real_escape_string($con, $_POST['ing_ib']) : "";
    $sqg = isset($_POST['ing_g']) ? mysqli_real_escape_string($con, $_POST['ing_g']) : "";
    $sqbn = isset($_POST['ing_bn']) ? mysqli_real_escape_string($con, $_POST['ing_bn']) : "";
    $sqlev = isset($_POST['ing_lev']) ? mysqli_real_escape_string($con, $_POST['ing_lev']) : "";
    $sqprijs = isset($_POST['ing_prijs']) ? mysqli_real_escape_string($con, $_POST['ing_prijs']) : "";
}

$location = "index.php?p=toevoegen";

//is q ingevuld?
if(isset($_GET['q'])){
    switch ($_GET['q']){
        //delete een artikel
        case("del") :
            if($sqid != ""){
                $query = "DELETE FROM Artikelen WHERE ArtNR = $sqid";
                $result = mysqli_query($con, $query);
                if(mysqli_error($con)){//ging er iets fout?
                    $location = "index.php?p=toevoegen&res=failed";
                }
                $location = "index.php?p=toevoegen&res=deleted";
            }
            break;
        //voeg een artikel toe
        case("add") :
            if (isset ($_POST['ing_submit'])){//is de submit button gedrukt?
                $query = "INSERT INTO Artikelen(ART_Naam, ART_TechnischeVoorraad, ART_InBestelling, ART_Gereserveerd, ART_BestelNiveau, ART_Leverancier, ART_prijs) VALUES ('$sqnaam', $sqtv, $sqib, $sqg, $sqbn, $sqlev, $sqprijs);";
                $results = mysqli_query($con, $query);
                if(mysqli_error($con)){//ging er iets fout?
                    $_SESSION['ing'] = $_POST;//sla post op als sessie en stuur terug
                    $location = "index.php?p=ingredientform&res=failed";
                }
                unset($_SESSION['ing']);//alles ging goed delete sessie
                $location = "index.php?p=toevoegen&res=added";
            }
            break;
        case("mod") :
            if (isset($_POST['ing_submit']) && $sqid != ""){//is de submit button gedrukt en is er een id aanwezig
                $query = "UPDATE Artikelen SET ART_Naam='$sqnaam', ART_TechnischeVoorraad=$sqtv, ART_InBestelling=$sqib, ART_Gereserveerd=$sqg, ART_BestelNiveau=$sqbn, ART_Leverancier=$sqlev, ART_prijs=$sqprijs WHERE ArtNR=$sqid;";
                $result = mysqli_query($con, $query);
                if(mysqli_error($con)) {//ging de query fout?
                    $_SESSION['ing'] = $_POST;//sla post op als sessie en stuur terug
                    $location = "index.php?p=ingredientform&id=$sqid&res=failed";
                }
                unset($_SESSION['ing']);//alles ging goed, verdwijder sessie
                $location = "index.php?p=toevoegen&res=modified";
            }
            break;
    }
}
header('location:'.$location);//stuur terug as q niet is gezet
