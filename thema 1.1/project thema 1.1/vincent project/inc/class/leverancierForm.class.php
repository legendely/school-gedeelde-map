<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 31-10-2014
 * Time: 06:00
 */

//initializeer id
if (isset($_POST['lev_id'])){
    //als post id is gezet
    $sqid = mysqli_real_escape_string($con, $_POST['lev_id']);
} else if (isset($_GET['id'])){
    //als get id is gezet
    $sqid = mysqli_real_escape_string($con, $_GET['id']);
} else {
    //als geen id is gezet
    $sqid = "";
}

//initializeer alle variabelen
if(isset ($_POST['lev_submit'])) {
    $sqnaam = isset($_POST['lev_naam']) ? mysqli_real_escape_string($con, $_POST['lev_naam']) : "";
    $sqadres = isset($_POST['lev_adres']) ? mysqli_real_escape_string($con, $_POST['lev_adres']) : "";
    $sqpost = isset($_POST['lev_post']) ? mysqli_real_escape_string($con, $_POST['lev_post']) : "";
    $sqplaats = isset($_POST['lev_plaats']) ? mysqli_real_escape_string($con, $_POST['lev_plaats']) : "";
    $sqmail = isset($_POST['lev_mail']) ? mysqli_real_escape_string($con, $_POST['lev_mail']) : "";
    $sqtel = isset($_POST['lev_tel']) ? mysqli_real_escape_string($con, $_POST['lev_tel']) : "";
}

$location = "index.php?p=toevoegen";

if(isset($_GET['q'])){
    switch ($_GET['q']){
        case("del") ://verdwijderen
            if(!empty($sqid)){//als een id is gezet
                $query = "DELETE FROM leverancier WHERE LevNR = $sqid";
                $result = mysqli_query($con, $query);
                if(mysqli_error($con)){//gaat de query fout?
                    $location = "index.php?p=toevoegen&res=failed";
                }
                $location = "index.php?p=toevoegen&res=deleted";
            }
            break;
        case("add") ://toevoegen
            if (isset ($_POST['lev_submit'])){//is submit gedrukt
                $query = "INSERT INTO leverancier (LEV_Adres, LEV_Mail, LEV_Naam, LEV_Plaats, LEV_Postcode, LEV_Telefoonnummer) VALUES ('$sqadres','$sqmail','$sqnaam', '$sqplaats','$sqpost', '$sqtel');";
                $result = mysqli_query($con, $query);
                
                if(mysqli_error($con)){//gaat de query fout?
                    $_SESSION['res'] = $_POST;//sla post op en stuur terug
                    $location = "index.php?p=leverancierform&res=failed";
                }
                $location = "index.php?p=toevoegen&res=added";
            }
            break;
        case("mod") ://veranderen
            if (isset ($_POST['lev_submit']) && $sqid != ""){//is submit gedrukt en is sqid aanwezig
                $query = "UPDATE leverancier SET LEV_Naam='$sqnaam', LEV_Adres='$sqadres', LEV_Postcode='$sqpost', LEV_Plaats='$sqplaats', LEV_Mail='$sqmail', LEV_Telefoonnummer='$sqtel' WHERE LevNR=$sqid;";
                $result = mysqli_query($con, $query);
                if(mysqli_error($con)) {//gaat er iets fout?
                    $_SESSION['res'] = $_POST;//sla post op en stuur terug
                    $location = "index.php?p=leverancierform&id=$sqid&res=failed";
                }
                $location = "index.php?p=toevoegen&res=modified";
            }
            break;
    }
}
header('location:'.$location);//q is niet gezet, stuur terug

?>