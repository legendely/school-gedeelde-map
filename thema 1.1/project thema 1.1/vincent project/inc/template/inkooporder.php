<div class="content">
<?php

if(!isset($_SESSION['gegevens'])){
    header ('location: index.php');
}

//Alleen beheerders hebben toegang tot deze pagina. Geen beheerder: terugsturen naar index.php
if($gegevens['Afdeling'] != '1' && $gegevens['Afdeling'] != '4' && $gegevens['Afdeling'] != '7'){
    header ('location: index.php');
}

    if(isset($_GET['res'])){
        echo '<center>';
        if($_GET['res'] == 'failed'){
            echo '<center><div class="error">Kon object niet wijzigen!</div></center>';
        } else if ($_GET['res'] == 'success'){
            echo '<center><div class="success">Bestelorder is geplaatst.</div></center>';
        }
        echo '</center>';
    }

    /**
     * Functie makeSelect
     * maak een select lijst met alle artikelen en zet de default geselecteerde
     *
     * @param null $selected - wat geselecteerd moet worden (null als er niets geselecteerd moet worden)
     * @return string - een selectie lijst in HTML
     */
    function makeSelect($selected = null){
        global $con;
        $query = "SELECT * FROM Artikelen";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) == 0) {header("location: index.php?p=body&res=noarts");}//er zijn geen artikelen je kunt dus ook geen gerecht maken
        $toret = "<select name=\"ArtNR\" class=\"dropdownveld\">";
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
?>
    <form action="index.php?a=inkooporder" method="POST">
        <table>
            <h2>Inkooporder aanmaken<h2>
            <tr><td>Artikel nummer:</td><td><?php echo makeSelect(); ?></td></tr>
            <tr><td>Aantal:</td><td><input type="number" class="invoerveld" value="" name="Aantal" required></td></tr>
        </table><br>
        <input type="submit" class="submit" value="Inkooporder maken" name="ink_submit" /><br><br>
    </form>
</div>