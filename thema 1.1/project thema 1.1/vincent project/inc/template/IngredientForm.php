<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 31-10-2014
 * Time: 22:54
 */

if($gegevens['Afdeling'] != 1){//check of de gebruiker admin is
    header ('location: index.php');
}

$action = "index.php?a=ingredientForm&q=add";

if (isset($_GET['id'])){//als een id is gezet; verkrijg alle gegevens uit de database die bij dit id horen
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM Artikelen WHERE ArtNR = $id;";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 1){
        $row = mysqli_fetch_assoc($result);
        $qnaam = $row['ART_Naam'];
        $qtv = $row['ART_TechnischeVoorraad'];
        $qib = $row['ART_InBestelling'];
        $qg = $row['ART_Gereserveerd'];
        $qbn = $row['ART_BestelNiveau'];
        $qlev = $row['ART_Leverancier'];
        $qprijs = $row['ART_Prijs'];
        $action = "index.php?a=ingredientForm&q=mod";
    } else {
        header("index.php?p=toevoegen&res=failed");
    }
}
//predefine alle gegevens (is er een sessie aanwezig ? ja dan zet deze gegeven vast. nee dan kijk of er resultaat verkregen is van een query? ja dan zet dit vast. nee dan zet niets.
$ingnaam = isset($_SESSION['ing']['ing_naam']) ? $_SESSION['ing']['ing_naam'] : isset($qnaam) ? $qnaam : "";
$ingtv = isset($_SESSION['ing']['ing_tv']) ? $_SESSION['ing']['ing_tv'] : isset($qtv) ? $qtv : "";
$ingib = isset($_SESSION['ing']['ing_ib']) ? $_SESSION['ing']['ing_ib'] : isset($qib) ? $qib : "";
$ingg = isset($_SESSION['ing']['ing_g']) ? $_SESSION['ing']['ing_g'] : isset($qg) ? $qg : "";
$ingbn = isset($_SESSION['ing']['ing_bn']) ? $_SESSION['ing']['ing_bn'] : isset($qbn) ? $qbn : "";
$inglev = isset($_SESSION['ing']['ing_lev']) ? $_SESSION['ing']['ing_lev'] : isset($qlev) ? $qlev : "";
$ingprijs = isset($_SESSION['ing']['ing_prijs']) ? $_SESSION['ing']['ing_prijs'] : isset($qprijs) ? $qprijs : "";

/**
 * Functie makeSelect
 * maak een select lijst met alle leveranciers en zet de default geselecteerde
 *
 * @param null $selected - wat geselecteerd moet worden (null als er niets geselecteerd moet worden)
 * @return string - een selectie lijst in HTML
 */
function makeSelect(){
    global $inglev, $con;
    $query = "SELECT * FROM Leverancier";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 0) {header("location: index.php?p=toevoegen&res=nolevs");}//als er geen leveranciers zijn kun je geen ingredient toevoegen.
    echo "<select name=\"ing_lev\" class=\"dropdownveld\">";
    while($row = mysqli_fetch_assoc($result)){//alle leveranciers verkrijgen
        if($inglev != "" && $row['LevNR'] == $inglev){
            echo "<option value={$row['LevNR']} selected>{$row['LEV_Naam']} {$row['LEV_Plaats']} {$row['LEV_Adres']}</option>";//leverancier selecteren
        } else {
            echo "<option value={$row['LevNR']}>{$row['LEV_Naam']} {$row['LEV_Plaats']}</option>";//gewoon default.
        }
    }
    echo "</select>";
}
?>

<div class="content">
    <center>
        <h2>Ingredient Toevoegen/Wijzigen</h2>
        <form action="<?php echo $action;?>" method="post">
            <?php if(isset($_GET['res']) && $_GET['res'] == 'failed'){echo '<div class="error">Er ging iets verkeerd! Probeer opnieuw.</div><br>';}
            if(isset($_GET['id'])){echo "<input type=\"hidden\" name=\"ing_id\" value=\"{$_GET['id']}\">";}?>
            <table>
                <tr><td>Ingredient Naam</td>
                    <td><input type="text" class="invoerveld" name="ing_naam" placeholder="Naam" required autofocus value="<?php echo $ingnaam; ?>"></td></tr>
                <tr><td>Ingredient Prijs</td>
                    <td><input type="text" class="invoerveld" name="ing_prijs" placeholder="Prijs" required value="<?php echo $ingprijs; ?>"></td></tr>
                <tr><td>Ingredient Leverancier</td>
                    <td><?php makeSelect(); ?></td></tr>
                <tr><td>Ingredient In voorraad</td>
                    <td><input type="number" class="invoerveld" name="ing_tv" placeholder="Technische Voorraad" min="0" required value="<?php echo $ingtv; ?>"></td></tr>
                <tr><td>Ingredient In bestelling</td>
                    <td><input type="number" class="invoerveld" name="ing_ib" placeholder="In bestelling" min="0" required value="<?php echo $ingib; ?>"></td></tr>
                <tr><td>Ingredient Gereserveerd</td>
                    <td><input type="number" class="invoerveld" name="ing_g" placeholder="Gereserveerd" min="0" required value="<?php echo $ingg; ?>"></td></tr>
                <tr><td>Ingredient Bestelniveau</td>
                    <td><input type="number" class="invoerveld" name="ing_bn" placeholder="Besteniveau" min="0" required value="<?php echo $ingbn; ?>"></td></tr>
                <tr><td></td>
                    <td><button type="reset" class="submit">Opnieuw</button> <button type="submit" name="ing_submit" class="submit">Opslaan</button></td></tr>
            </table>
        </form>
    </center>
</div>

