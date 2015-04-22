<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 3-11-2014
 * Time: 13:54
 */

if($gegevens['Afdeling'] != 1){//check of de gebruiker admin is
    header ('location: index.php');
}

$action = "index.php?a=gerechtForm&q=add";

if (isset($_GET['id'])){//als een id is gezet; verkrijg alle gegevens uit de database die bij dit id horen
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM Gerecht WHERE GerNR = $id;";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 1){//als er 1 gerecht is met dit id
        $row = mysqli_fetch_assoc($result);
        $qnaam = $row['GER_Naam'];
        $qprijs = $row['GER_Prijs'];
        $qbes = $row['GER_Beschrijving'];

        //verkrijg ale ingredienten die bij dit gerecht horen
        $query2 = "SELECT * FROM Aantalingredienten a WHERE GerNR = $id;";
        $result2 = mysqli_query($con, $query2);
        while($row = mysqli_fetch_assoc($result2)){//alle ingredient nummers en hoeveelheden in een associative array zetten formaat=(id=>aantal)
            $ingredienten[$row['ArtNR']] = $row['ING_Aantal'];
        }
        $action = "index.php?a=gerechtForm&q=mod";
    } else {//oeps er ging iets fout
        header("index.php?p=toevoegen&res=failed");
    }
}

//predefine alle gegevens (is er een sessie aanwezig ? ja dan zet deze gegeven vast. nee dan kijk of er resultaat verkregen is van een query? ja dan zet dit vast. nee dan zet niets.
$gernaam = isset($_SESSION['ger']['ger_naam']) ? $_SESSION['ger']['ger_naam'] : (isset($qnaam) ? $qnaam : "");
$gerbes = isset($_SESSION['ger']['ger_bes']) ? $_SESSION['ger']['ger_bes'] : (isset($qbes) ? $qbes : "");
$gerprijs = isset($_SESSION['ger']['ger_prijs']) ? $_SESSION['ger']['ger_prijs'] : (isset($qprijs) ? $qprijs : "");
$gering = isset($_SESSION['ger']['ger_ing']) ? $_SESSION['ger']['ger_ing'] : (isset($ingredienten) ? $ingredienten : "");
$selectRows = 1;//er kunnen nooit meer ingredienten in een gerecht zitten dan er artikelen zijn.
$canAdd = true;

/**
 * Functie makeSelect
 * maak een select lijst met alle artikelen en zet de default geselecteerde
 *
 * @param null $selected - wat geselecteerd moet worden (null als er niets geselecteerd moet worden)
 * @return string - een selectie lijst in HTML
 */
function makeSelect($selected = null){
    global $con, $selectRows;
    $query = "SELECT * FROM Artikelen";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 0) {header("location: index.php?p=toevoegen&res=nolevs");}//er zijn geen artikelen je kunt dus ook geen gerecht maken
    $selectRows = $rows;
    $toret = "<select name=\"ingredient[]\" class=\"dropdownveld\">";
    while($row = mysqli_fetch_assoc($result)){//verkrijg alle ingredienten en maak een lijst
        if($selected != null && $row['ArtNR'] == $selected){
            $toret .= "<option value={$row['ArtNR']} selected>{$row['ArtNR']} {$row['ART_Naam']}</option>";//zet de geselecteerde
        } else {
            $toret .= "<option value={$row['ArtNR']}>{$row['ArtNR']} {$row['ART_Naam']}</option>";
        }
    }
    $toret .= "</select>";
    return $toret;
}

/**
 * Functie getIngredienten
 * als variabel $gering gezet is dan zet deze alvast als value in een ingredienten aantal field. Als totaal ingredienten > rijen met velden dan geef een nieuw veld weer zo niet dan niet.
 * mocht er geen ingredient gezet zijn (in geval van een nieuwe gerecht) weergeef een lege input field en zet deze als required
 *
 * @return string
 */
function getIngredienten(){
    global $gering, $selectRows, $canAdd;
    $totalrows = 0;
    $toret = "";
    if ($gering != ""){//is gerecht ingredienten gezet?
        foreach($gering as $key => $value){//ga door de associative array
            $toret .= '<tr><td>' . makeSelect($key) . '</td><td><input type="number" class="invoerveld" name="hoeveelheid[]" placeholder="Hoeveelheid" value="'.$value.'"></td><td><input type="hidden" name="id" value="'.$key.'"/><button type="submit" name="ger_delIng" class="submit">Verwijder Ingredient</button></td></tr>';
            $totalrows++;
        }
    }
    if ($totalrows < $selectRows) {//als totaal rijen met velden kleiner is dan het aantal ingredienten
        $add = $totalrows == 0 ? "required" : "";//is er al een rij aanwezig met ingredienten zo nee zet de lege rij als required
        $toret .= "<tr><td>" . makeSelect() . "</td><td><input type=\"number\" class=\"invoerveld\" name=\"hoeveelheid[]\" placeholder=\"Hoeveelheid\" ". $add ."></td></tr>";
        $totalrows++;//voeg een nieuwe lege velden rij toe
        if ($totalrows == $selectRows){//als aantal veld rijen evenveel is als het aantal ingredienten dan kun je geen nieuwe rij toevoegen
            $canAdd = false;
        }
    } else {//als aantal veld rijen evenveel is als het aantal ingredienten dan kun je geen nieuwe rij toevoegen
        $canAdd = false;
    }
    return $toret;
}
?>

<div class="content">
    <center>
        <h2>Ingredient Toevoegen/Wijzigen</h2>
        <form action="<?php echo $action;?>" method="post">
            <?php if(isset($_GET['res']) && $_GET['res'] == 'failed'){echo '<div class="error">Er ging iets verkeerd! Probeer opnieuw.</div><br>';}
            if(isset($_GET['id'])){echo "<input type=\"hidden\" name=\"ger_id\" value=\"{$_GET['id']}\">";}else if(isset($_SESSION['ger']['ger_id'])){ echo "<input type=\"hidden\" name=\"ger_id\" value=\"{$_SESSION['ger']['ger_id']}\">";} ?>
            <table>
                <tr><td>Gerecht Naam</td><td><input type="text" class="invoerveld" name="ger_naam" placeholder="Naam" required autofocus value="<?php echo $gernaam; ?>"></td></tr>
                <tr><td>Gerecht Prijs</td><td><input type="text" class="invoerveld" name="ger_prijs" placeholder="Prijs" required value="<?php echo $gerprijs; ?>"></td></tr>
                <tr><td>Gerecht Beschrijving</td><td><textarea class="invoerarea" name="ger_bes" placeholder="Geef hier een beschrijving van het gerecht"><?php echo $gerbes; ?></textarea></td></tr>
                <tr><td>Gerecht ingredienten:</td><td></td></tr>
                <?php echo getIngredienten(); ?>
                <tr><td></td><td><button type="reset" class="submit">Opnieuw</button> <button type="submit" name="ger_submit" class="submit">Opslaan</button> <?php /*als je nog een rij kan toevoegen, weergeef de button*/ if ($canAdd){ echo '<button type="submit" name="ger_addIng" class="submit">Extra ingredient</button>'; } ?></td></tr>
            </table>
        </form>
    </center>
</div>

