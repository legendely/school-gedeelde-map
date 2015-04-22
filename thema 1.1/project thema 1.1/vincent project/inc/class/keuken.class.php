<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 5-11-2014
 * Time: 12:37
 */


//is keuken submit gedrukt?
if (isset($_POST['keuken_submit'])){
    $id = mysqli_real_escape_string($con, $_GET['id']);//prepereer id voor mysql
    if (!updateStock($id)){header('location: index.php?p=keuken&res=fail');}//update voorraad is fout gegaan
    if (!updateStatus($id)){header('location: index.php?p=keuken&res=fail');}//update status is fout gegaan
    header('location: index.php?p=keuken');//alles ging goed stuur terug naar keuken
}

/**
 * Functie updateStatus
 * Update de status van een bestelling naar bezorgen
 *
 * @param $id - het id van de te wijzigen bestelling
 * @return true - als alles goed ging
 */
function updateStatus($id){
    global $con;
    $query = "UPDATE Bestelling SET BEST_Status = 'bezorgen' WHERE BestNR = $id";
    mysqli_query($con, $query);
    if (mysqli_error($con)){
        return false;
    }
    return true;
}

/**
 * Functie updateStock
 * upadte de technische en gereserveerde voorraden NAV een bestelling
 *
 * @param $id - de id van het bestelling
 * @return true - als alles goed is gegaan
 */
function updateStock($id){
    global $con;
    $query = "SELECT * FROM AantalVerkocht WHERE BestNR = $id;";//verkrijg de gerechten van een bestelling
    $res = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($res)) {//voor alle bestellingen die een id hebben dat gelijk is aan het opgegeven id
        $query2 = "SELECT * FROM Gerecht g JOIN Aantalingredienten a ON g.GerNR = a.GerNR WHERE g.GerNR = {$row['GerNR']};";//verkrijg alle gerechten en aantal ingredienten van een het gerecht
        $res2 = mysqli_query($con, $query2);
        while($row2 = mysqli_fetch_assoc($res2)){
            $query3 = "SELECT * FROM Artikelen WHERE ArtNR = {$row2['ArtNR']}";//verkrijg alle details van een artikel
            $row3 = mysqli_fetch_assoc(mysqli_query($con, $query3));

            $aantalIngredient = $row['Aantal'] * $row2['ING_Aantal'];//aantal gebruikte ingredienten is aantal van het bestelde gerecht * hoeveelheid van het artikel dat gebruikt is in een gerecht
            $newTV = $row3['ART_TechnischeVoorraad'] - $aantalIngredient;
            $newGER = $row3['ART_Gereserveerd'] - $aantalIngredient;
            $query4 = "UPDATE Artikelen SET ART_TechnischeVoorraad=$newTV ,ART_Gereserveerd=$newGER WHERE ArtNR = {$row3['ArtNR']};";//update de hoeveelheden technische en gereserveerde voorraad
            mysqli_query($con, $query4);
            if (mysqli_error($con)){
                return false;
            }
        }
        if (mysqli_error($con)){
            return false;
        }
    }
    if(mysqli_error($con)){
        return false;
    }
    return true;
}

?>