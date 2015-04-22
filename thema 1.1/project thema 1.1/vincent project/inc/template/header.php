<?php
/* Geschreven door:			Thijs Kuilman
 * Studentnummer:					327154
 *
 * Doel van dit bestand:
 * Dit is de header van elke pagina. Dit wordt geinclude zodat het later makkelijker wordt om voor elke pagina de header te veranderen. De header bestaat uit:
 * - Het menu
 * - Het logo
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <title>EatIt</title>
        <link rel="stylesheet" type="text/css" href="inc/template/css/style.css">
        <link rel="stylesheet" type="text/css" href="inc/template/css/tableStyle.css">
        <link rel="stylesheet" type="text/css" href="inc/template/css/jquery.mCustomScrollbar.css">


        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="inc/template/js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="inc/template/js/main.js"></script>

        <script type="text/javascript" src="resources/ckeditor/ckeditor.js"></script>

        <meta charset="UTF-8">

        <meta name="author" content="Team 1F" />
        <meta name="language" content="NL">
        <meta name="copyright" content="HanzeHogeschool">
        <meta name="description" content="Op deze website van EatIt kunt u gerechten bekijken en bestellen.">
    </head>

    <body>

    <!-- Gegevens van de gebruiker opslaan -->
    <?php
    if(isset($_SESSION['soortgebruiker'])){
        if($_SESSION['soortgebruiker'] == "klant"){
            if(isset($_SESSION['gegevens'])){
                $gegevens = ($_SESSION['gegevens']);
                // Alle gegevens van de ingelogde gebruiker opslaan in een sessie
                $query = "SELECT * FROM Klant WHERE KL_Mail = '" . $gegevens['KL_Mail'] . "' ";
                $result = mysqli_query($con, $query);
                $_SESSION['gegevens'] = mysqli_fetch_array($result);
                $gegevens = ($_SESSION['gegevens']);
            }
        }

        if($_SESSION['soortgebruiker'] == "medewerker"){
            if(isset($_SESSION['gegevens'])){
                $gegevens = ($_SESSION['gegevens']);
                // Alle gegevens van de ingelogde gebruiker opslaan in een sessie
                $query = "SELECT * FROM Medewerkers WHERE MED_Mail = '" . $gegevens['MED_Mail'] . "' ";
                $result = mysqli_query($con, $query);
                $_SESSION['gegevens'] = mysqli_fetch_array($result);
                $gegevens = ($_SESSION['gegevens']);
            }
        }
    }
    
    ?>

    <div class="container">
        <div class="header">
            <a href="index.php"><img src="inc/template/img/logo.png" id="logo"></a>

            <!-- Het menu. Wordt aangepast op basis of je bent ingelogd. -->
            <div class="menu">
                <?php
                    // Niet ingelogd: krijg een inlog en registreer optie te zien
                    if(!isset($_SESSION['gegevens'])){
                        echo '<a href="index.php?p=login">Inloggen</a> |
                        <a href="index.php?p=register">Registreren</a>';
                    } else {
                        // Ingelogd: je krijgt het volledige menu te zien (instellingen, winkelwagen, uitloggen)
                        //$beheerder =  (($gegevens['permissies'] == 'beheerder') ? '<a href="?p=beheerder">Beheerderspaneel</a> | ' :"");
                        if($_SESSION['soortgebruiker'] == "klant"){
                            echo "Welkom, {$gegevens['KL_Voornaam']} | <a href=\"?p=index.php\">Home</a> | <a href=\"?p=instellingen\">Instellingen</a> |  <a href=\"?p=winkelwagen\">Winkelwagen</a> | <a href=\"?p=uitloggen\">Uitloggen</a>";
                        }

                        if($_SESSION['soortgebruiker'] == "medewerker"){
                            echo "Welkom, {$gegevens['MED_Voornaam']} (Medewerker) | <a href=\"?p=index.php\">Home</a> | " . (($gegevens['Afdeling'] == '1')?'<a href="?p=beheerder">Medewerkerbeheer</a> | ':"") . (($gegevens['Afdeling'] == '1')?'<a href="?p=toevoegen">Gegevensbeheer</a> | ':"") . (($gegevens['Afdeling'] == 6 || $gegevens['Afdeling'] == 1)?'<a href="?p=keuken">Geplaatste Bestellingen</a> | ':"") . (($gegevens['Afdeling'] == 3 || $gegevens['Afdeling'] == 1 || $gegevens['Afdeling'] == 7)?'<a href="?p=inkoopfactuur">Inkoopfactuur</a> | ':"") . (($gegevens['Afdeling'] == 7 || $gegevens['Afdeling'] == 1)?'<a href="?p=BAL">BAL</a> | ':"")  . (($gegevens['Afdeling'] == 7 || $gegevens['Afdeling'] == 1)?'<a href="?p=inkooporder">Inkooporder maken</a> | ':"") .  (($gegevens['Afdeling'] == '2' || $gegevens['Afdeling'] == 1)?'<a href="?p=routeplanning">Routeplanning</a> | ':"")  . " <a href=\"?p=instellingen\">Instellingen</a> | <a href=\"?p=uitloggen\">Uitloggen</a>";
                        }
                    }
                ?>
            </div>
        </div>