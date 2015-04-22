<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 31-10-2014
 * Time: 04:23
 */

if($gegevens['Afdeling'] != 1){//check of de gebruiker admin is
    header ('location: index.php');
}


$action = "index.php?a=leverancierForm&q=add";

if (isset($_GET['id'])){//als een id is gezet; verkrijg alle gegevens uit de database die bij dit id horen
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM Leverancier WHERE LevNR = $id;";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 1){
        $row = mysqli_fetch_assoc($result);
        $qnaam = $row['LEV_Naam'];
        $qadres = $row['LEV_Adres'];
        $qpost = $row['LEV_Postcode'];
        $qplaats = $row['LEV_Plaats'];
        $qmail = $row['LEV_Mail'];
        $qtel = $row['LEV_Telefoonnummer'];
        $action = "index.php?a=leverancierForm&q=mod";
    } else {
        header("index.php?p=toevoegen&res=failed");
    }
}
//predefine alle gegevens (is er een sessie aanwezig ? ja dan zet deze gegeven vast. nee dan kijk of er resultaat verkregen is van een query? ja dan zet dit vast. nee dan zet niets.
$levnaam = isset($_SESSION['lev']['lev_naam']) ? $_SESSION['lev']['lev_naam'] : isset($qnaam) ? $qnaam : "";
$levadres = isset($_SESSION['lev']['lev_adres']) ? $_SESSION['lev']['lev_adres'] : isset($qadres) ? $qadres : "";
$levpost = isset($_SESSION['lev']['lev_post']) ? $_SESSION['lev']['lev_post'] : isset($qpost) ? $qpost : "";
$levplaats = isset($_SESSION['lev']['lev_plaats']) ? $_SESSION['lev']['lev_plaats'] : isset($qplaats) ? $qplaats : "";
$levmail = isset($_SESSION['lev']['lev_mail']) ? $_SESSION['lev']['lev_mail'] : isset($qmail) ? $qmail : "";
$levtel = isset($_SESSION['lev']['lev_tel']) ? $_SESSION['lev']['lev_tel'] : isset($qtel) ? $qtel : "";
?>

<div class="content">
    <center>
    <h2>Leverancier Toevoegen/Wijzigen</h2>
    <form action="<?php echo $action;?>" method="post">
        <?php if(isset($_GET['res']) && $_GET['res'] == 'failed'){echo '<div class="error">Er ging iets verkeerd! Probeer opnieuw.</div><br>';}
         if(isset($_GET['id'])){echo "<input type=\"hidden\" name=\"lev_id\" value=\"{$_GET['id']}\">";}?>
        <table>
            <tr><td>Leverancier Naam</td>
                <td><input type="text" class="invoerveld" name="lev_naam" placeholder="Naam" required autofocus value="<?php echo $levnaam; ?>"></td></tr>
            <tr><td>Leverancier Adres</td>
                <td><input type="text" class="invoerveld" name="lev_adres" placeholder="Adres" required value="<?php echo $levadres; ?>"></td></tr>
            <tr><td>Leverancier Postcode</td>
                <td><input type="text" class="invoerveld" name="lev_post" placeholder="Postcode" required value="<?php echo $levpost; ?>"></td></tr>
            <tr><td>Leverancier Plaats</td>
                <td><input type="text" class="invoerveld" name="lev_plaats" placeholder="Plaats" required value="<?php echo $levplaats; ?>"></td></tr>
            <tr><td>Leverancier E-mail</td>
                <td><input type="email" class="invoerveld" name="lev_mail" placeholder="E-Mail" value="<?php echo $levmail; ?>"></td></tr>
            <tr><td>Leverancier Telefoon</td>
                <td><input type="number" class="invoerveld" name="lev_tel" placeholder="Telefoon" min="0" value="<?php echo $levtel; ?>"></td></tr>
            <tr><td></td>
                <td><button type="reset" class="submit">Opnieuw</button> <button type="submit" name="lev_submit" class="submit">Opslaan</button></td></tr>
        </table>
    </form>
    </center>
</div>

