<?php
/**
 * Created by PhpStorm.
 * Date: 28-10-2014
 * Time: 11:00
 */
if($gegevens['Afdeling'] != 1){//check of de gebruiker admin is
    header ('location: index.php');
}
?>

<div class="content">
    <?php if (isset($_GET['res'])){//is er resultaat van een actie weergeef dit dan
        echo '<center>';
        switch($_GET['res']) {
            case("added") :
                echo '<div class="success">Object toegevoegd aan database.</div><br>';
                break;
            case("modified") :
                echo '<div class="success">Object gewijzigd.</div><br>';
                break;
            case("deleted") :
                echo '<div class="success">Object gedelete</div><br>';
                break;
            case("failed") :
                echo '<div class="error">Kon object niet wijzigen!</div><br>';
                break;
        }
        echo '</center>';
    }?>
    <div class="box">
        <div class="edittabtitle">
            <p class="title">Gerechten verwijderen en/of aanpassen</p>
            <p class="blue">Hier kunt u gerechten verwijderen uit en/of aanpassen in de database.</p>
            <a href="index.php?p=gerechtForm"><div id="newGerecht" class="newitem">Gerecht toevoegen</div></a>
        </div>
        <table class="edittable" rules="groups">
            <thead>
            <tr>
                <th class="a">Gerecht</th>
                <th class="b">GerechtId</th>
                <th class="c">Prijs</th>
                <th class="d">Invoorraad</th>
                <th class="e"></th>
            </tr>
            </thead>

            <tbody class="editbody">
            <?php
            $query = "SELECT * FROM Gerecht;";
            $result = mysqli_query($con, $query);
            $rows = 0;
            while($row = mysqli_fetch_assoc($result)){//geef alle gerechten weer
                $query = "SELECT * FROM Aantalingredienten a JOIN Artikelen i ON i.ArtNR = a.ArtNR WHERE a.GerNR = {$row['GerNR']};";
                $res2 = mysqli_query($con, $query);
                $stocked = 0;
                if (!$res2) continue;
                unset($mogelijk);
                while($row2 = mysqli_fetch_assoc($res2)){
                    $voorraad = $row2['ART_TechnischeVoorraad'] - $row2['ART_Gereserveerd'];
                    if ($row2['ING_Aantal'] > $voorraad) {
                        $stocked = 0;
                        break;
                    }

                    $am = $voorraad / $row2['ING_Aantal'];
                    if (!isset($mogelijk) || $mogelijk > $am) {
                        $stocked = floor($am);
                        $mogelijk = floor($am);
                    }
                }

                $levnr = $row["GerNR"];
                echo "<tr>
                <td class=\"a\">{$row['GER_Naam']}</td>
                <td class=\"b\">{$row['GerNR']}</td>
                <td class=\"c\">{$row['GER_Prijs']}</td>
                <td class=\"d\">{$stocked}</td>
                <td class=\"e\"><a href=\"index.php?p=gerechtForm&id=$levnr\"><img src=\"inc/template/img/otf_edit.svg\" class=\"editico\"></a><a href=\"index.php?a=gerechtform&q=del&id=$levnr\"><img src=\"inc/template/img/otf_delete.svg\" class=\"delico\"></a></td>
            </tr>";
                $rows++;
            }?>
            </tbody>
        </table>
        <div class="edittabfooter"><?php echo $rows?> gerecht(en) in totaal.</div>
    </div>

    <div class="box">
        <div class="edittabtitle">
            <p class="title">Ingredienten verwijderen en/of aanpassen</p>
            <p class="blue">Hier kunt u Ingredienten verwijderen uit en/of aanpassen in de database.</p>
            <a href="index.php?p=ingredientform"><div id="newIngredient" class="newitem">Ingredient toevoegen</div></a>
        </div>
        <table class="edittable" rules="groups">
            <thead>
            <tr>
                <th class="a">Product</th>
                <th class="b">In voorraad</th>
                <th class="c">Prijs</th>
                <th class="d">Leverancier</th>
                <th class="e"></th>
            </tr>
            </thead>

            <tbody class="editbody">
            <?php
            $query = "SELECT * FROM Artikelen i JOIN Leverancier l ON l.LevNr = i.ART_Leverancier;";
            $result = mysqli_query($con, $query);
            $rows = 0;
            while($row = mysqli_fetch_assoc($result)){//geef alle artikelen weer
                $levnr = $row["ArtNR"];
                echo "<tr>
                <td class=\"a\">{$row['ART_Naam']}</td>
                <td class=\"b\">{$row['ART_TechnischeVoorraad']}</td>
                <td class=\"c\">{$row['ART_Prijs']}</td>
                <td class=\"d\">".substr($row['LEV_Naam'], 0, 12)."</td>
                <td class=\"e\"><a href=\"index.php?p=ingredientForm&id=$levnr\"><img src=\"inc/template/img/otf_edit.svg\" class=\"editico\"></a><a href=\"index.php?a=ingredientForm&q=del&id=$levnr\"><img src=\"inc/template/img/otf_delete.svg\" class=\"delico\"></a></td>
            </tr>";
                $rows++;
            }?>
            </tbody>
        </table>
        <div class="edittabfooter"><?php echo $rows?> ingredient(en) in totaal.</div>
    </div>

    <div class="box">
        <div class="edittabtitle">
            <p class="title">Leveranciers verwijderen en/of aanpassen</p>
            <p class="blue">Hier kunt u leveranciers verwijderen uit en/of aanpassen in de database.</p>
            <a href="index.php?p=leverancierForm"><div id="newLeverancier" class="newitem">Leverancier toevoegen</div></a>
        </div>
        <table class="edittable" rules="groups">
            <thead>
            <tr>
                <th class="a">Adres</th>
                <th class="b">Postcode</th>
                <th class="c">Plaats</th>
                <th class="d">Naam</th>
                <th class="e"></th>
            </tr>
            </thead>

            <tbody class="editbody">
            <?php
            $query = "SELECT * FROM Leverancier;";
            $result = mysqli_query($con, $query);
            $rows = 0;
            while($row = mysqli_fetch_assoc($result)){//geef alle leveranciers weer
                $levnr = $row["LevNR"];
                echo "<tr>
                <td class=\"a\">{$row['LEV_Adres']}</td>
                <td class=\"b\">{$row['LEV_Postcode']}</td>
                <td class=\"c\">{$row['LEV_Plaats']}</td>
                <td class=\"d\">".substr($row['LEV_Naam'], 0, 12)."</td>
                <td class=\"e\"><a href=\"index.php?p=leverancierForm&id=$levnr\"><img src=\"inc/template/img/otf_edit.svg\" class=\"editico\"></a><a href=\"index.php?a=leverancierform&q=del&id=$levnr\"><img src=\"inc/template/img/otf_delete.svg\" class=\"delico\"></a></td>
                    </tr>";
                $rows++;
            }
            ?>
            </tbody>
        </table>
        <div class="edittabfooter"><?php echo $rows?> leverancier(s) in totaal.</div>
    </div>
</div>