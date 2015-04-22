<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 5-11-2014
 * Time: 11:28
 */

	//Alleen beheerders en de inkoop afdeling hebben toegang tot deze pagina. Geen van deze? Terugsturen naar index.php
	if($gegevens['Afdeling'] != '6'){
	    if($gegevens['Afdeling'] != '1'){
	        header ('location: index.php');
	    }
	}
/**
 * Functie getOrders
 * verkrijg alle orders, gerechten en ingredienten die bij deze gerechten horen
 *
 * @return string - een lijst met alle orders, hun gerechten en de ingredienten van deze gerechten
 */
function getOrders() {
    return getBestellingen();
}

function getIngredientenFromRecept($Gernr){
    global $con;
    $query = "SELECT * FROM Aantalingredienten ai JOIN Artikelen ar ON ai.ArtNR = ar.ArtNR WHERE GerNR = $Gernr;";
    $result = mysqli_query($con, $query);
    $array = resToArray($result);
    $toret = "";
    for ($i = 0; $i < count($array); $i++) {
        $val = $array[$i];
        echo '<tr><td>Artikel:'.$val['ArtNR'].' '. $val['ART_Naam'] .' Hoeveelheid: '.$val['ING_Aantal'].'</td></tr>';
    }
    //return $toret;
}

function getReceptenFromBestelling($Bestelnr){
    global $con;
    $query = "SELECT * FROM AantalVerkocht a JOIN Gerecht g ON a.GerNR = g.GerNR WHERE BestNR = '$Bestelnr';";
    $result = mysqli_query($con, $query);
    $array = resToArray($result);
    $toret = '';
    for ($i = 0; $i < count($array); $i++) {
        $val = $array[$i];
        echo '<tr><td>Gerecht: ' . $val['GER_Naam'] . '</td><td>Aantal: ' . $val['Aantal'] . '</td></tr>';
        echo'<tr><td>&nbsp;</td></tr>';
        getIngredientenFromRecept($val['GerNR']);
        echo '<tr><td>&nbsp;</td></tr>';
        echo'<tr><td>&nbsp;</td></tr>';
    }
    echo '<tr><td><form action="'. "index.php?a=keuken&id=$Bestelnr" .'" method="post"><input class="submit" type="submit" name="keuken_submit" value="Klaar"></form></td></tr>';
    //return $toret;
}

function getBestellingen() {
    global $con;
    $query = "SELECT * FROM Bestelling WHERE BEST_Status='besteld';";
    $result = mysqli_query($con, $query);
    $array = resToArray($result);
    $toret = "";
    foreach ($array as $val) {
        echo '<tr><td><h2>Bestelling: '.$val['BestNR'].'</h2></td><td><h2>Klant: '.$val['KlantNR'].'</h2></td></tr>';
        getReceptenFromBestelling($val['BestNR']);
        echo '<tr class="UL"><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }
    //return $toret;
}

function resToArray($result){
    $toret = array();
    while($row = mysqli_fetch_assoc($result)){
        array_push($toret, $row);
    }
    return $toret;
}

?>

<div class="content">
    <?php if(isset($_GET['res'])) {//als er een error was en de bestelstatus kon niet worden gewijzigd
        if ($_GET['res'] == 'fail'){
            echo '<center><div class="error">Kon bestel status niet wijzigen! Probeer opnieuw.</div></center><br>';
        }
    }?>
    <table>
        <?php
        echo getOrders();
        ?>
    </table>

</div>