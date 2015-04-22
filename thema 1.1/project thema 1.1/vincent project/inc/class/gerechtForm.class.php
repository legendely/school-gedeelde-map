<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 3-11-2014
 * Time: 13:54
 */

//als gerechtAddIngredient is toegevoegd
if (isset($_POST['ger_addIng'])){
    //sla post op
    savePost();
    //stuur terug naar gerecht formulier
    $gerredId = $_POST['ger_id'];
    header("location: index.php?p=gerechtForm&id=$gerredId");
}

//als gerechtDelete Ingredient knop is ingedrukt
if (isset($_POST['ger_delIng'])){
    //sla post op en verdwijder data;
    savePost();
    unset($_SESSION['ger']['ger_ing'][$_POST['id']]);
    unset($_SESSION['ger']['id']);
    //stuur terug naar gerecht formulier
    $gerredId = $_POST['ger_id'];
    header("location: index.php?p=gerechtForm&id=$gerredId");
}
//als gerecht submit knop is ingedrukt prepereer alle variabelen voor mysql
if (isset($_POST['ger_submit'])){
    $sqnaam = isset($_POST['ger_naam']) ? mysqli_real_escape_string($con, $_POST['ger_naam']) : "";
    $sqprijs = isset($_POST['ger_prijs']) ? mysqli_real_escape_string($con, $_POST['ger_prijs']) : "";
    $sqbes = isset($_POST['ger_bes']) ? mysqli_real_escape_string($con, $_POST['ger_bes']) : "";
    $sqid = isset($_POST['ger_id']) ? mysqli_real_escape_string($con, $_POST['ger_id']) : "";
}

//als q is gezet
if (isset($_GET['q'])){
    switch($_GET['q']){
        //toevoegen gerecht
        case("add") :
            //voeg een gerecht toe en check of er iets is fout gegaan
            if (isset($_POST['ger_submit'])){
                if (!addGerecht()){
                    savePost();
                    header("location: index.php?p=gerechtForm&res=failed");
                    break;
                }
                //alles ging goed verdwijder de sessie
                unset($_SESSION['ger']);
                header("location: index.php?p=toevoegen&res=added");
            }
            break;
        //veranderen gerecht
        case("mod") :
            if (isset($_POST['ger_submit'])){
                //verander het gerecht en check of er iets is fout gegaan
                if(!modGerecht()){
                    savePost();
                    header("location: index.php?p=gerechtForm&q=mod&id=$sqid&res=failed");
                    break;
                }
                //alles ging goed, verdwijder de sessie
                unset($_SESSION['ger']);
                header("location: index.php?p=toevoegen&res=modified");
            }
            break;
        //verdwijderen gerecht
        case("del") :
            $id = $_GET['id'];
            //als iets fout is gegaan
            if (!deleteGerecht($id)){
                header("location: index.php?p=toevoegen&res=failed");
                break;
            }
            //verdwijder de sessie
            unset($_SESSION['ger']);
            header("location: index.php?p=toevoegen&res=deleted");
            break;
    }
}

/**
 * Functie modGerecht
 * pas een gerecht (en ingredienten) aan.
 *
 * @return true - als alles goed is verlopen
 */
function modGerecht(){
    global $sqnaam, $sqprijs, $sqbes, $sqid, $con;
    //update het gerecht
    $query = "UPDATE Gerecht SET GER_Naam='$sqnaam', GER_Prijs='$sqprijs', GER_Beschrijving='$sqbes' WHERE GerNR = $sqid;";
    $res = mysqli_query($con, $query);
    if (mysqli_error($con)){
        die("Kon gerechten niet updaten query:" . $query);
    }
    //delete alle ingredienten en voeg ze opnieuw toe.
    $res1 = deleteIngredients($sqid);
    $res2 = addIngredients($sqid);
    return ($res1 && $res2);
}

/**
 * Functie addGerecht
 * voeg een gerecht (en ingredienten) toe aan het database
 *
 * @return true - als alles goed is verlopen
 */
function addGerecht(){
    global $sqnaam, $sqprijs, $sqbes, $con;
    //voeg het gerecht toe
    $query = "INSERT INTO Gerecht(GER_Naam, GER_Prijs, GER_Beschrijving) VALUES ('$sqnaam', '$sqprijs', '$sqbes');";
    $res = mysqli_query($con, $query);
    if (mysqli_error($con)){
        return false;
    }
    //voeg alle ingredienten toe
    $gerId = mysqli_insert_id($con);
    return (addIngredients($gerId));
}

/**
 * Functie deleteGerecht
 * verdwijder een gerecht(en ingredienten) uit de database
 *
 * @param $id - de id van het te verdwijderen gerecht
 * @return true - als alles goed is verlopen
 */
function deleteGerecht($id){
    global $con;
    $id = mysqli_real_escape_string($con, $id);
    //verdwijder alle ingredienten
    if (!deleteIngredients($id)){
        return false;
    }

    //verdwijder het gerecht
    $query = "DELETE FROM Gerecht WHERE GerNR = $id;";
    mysqli_query($con, $query);
    if (mysqli_error($con)){
        return false;
    }
    return true;
}

/**
 * Functie deleteIngredients
 * verdwijder alle ingredienten die gelinkt zijn aan de opgegeven gerechtId
 *
 * @param $gerId - het gerecht waarvan de ingredienten verdwijderd moeten worden
 * @return true - als alles goed is verlopen
 */
function deleteIngredients($gerId){
    global $con;
    //verdwijder alle ingredienten waar de gerechtnr gelijk staat aan gerechtId
    $query = "DELETE FROM Aantalingredienten WHERE GerNR = $gerId;";
    mysqli_query($con, $query);
    if (mysqli_error($con)){
        return false;
    }
    return true;
}

/**
 * Functie aadIngredients
 * Voeg Link alle ingredienten aan een gerecht Nummer
 *
 * @param $gerId - Het gerecht ID waar de ingredienten aan gekoppeld moeten worden
 * @return true - als alles goed is verlopen
 */
function addIngredients($gerId){
    global $con;
    //ingredienten van post samenvoegen tot 1 associative array
    $ingredients = merge($_POST['ingredient'], $_POST['hoeveelheid']);
    $gerId = mysqli_real_escape_string($con, $gerId);
    //loop door alle ingredienten
    foreach ($ingredients as $key => $value){
        //voeg de ingredienten toe aan het database en link de gerecht eraan
        $query = "INSERT INTO Aantalingredienten(GerNR, ArtNR, ING_Aantal) VALUES ($gerId, $key, $value);";
        mysqli_query($con, $query);
        if (mysqli_error($con)){
            return false;
        }
    }
    return true;
}

/**
 * Functie SavePost
 *
 * Post opslaan als Sessie;
 * Hoeveelheid en ingredient worden vervangen door 1 associative array
 */
function savePost(){
    $_SESSION['ger'] = $_POST;
    $_SESSION['ger']['ger_ing'] = merge($_POST['ingredient'], $_POST['hoeveelheid']);
    unset($_SESSION['ger']['hoeveelheid']);
    unset($_SESSION['ger']['ingredient']);
}

/**
 * Functie merge
 * 2 arrays samenvoegen tot 1 array
 *
 * @param $array1 - keys array
 * @param $array2 - values array
 * @return Array (array1 => array2)
 */
function merge($array1, $array2){
    for($i = 0; $i < count($array1); $i++){
        //als de value leeg is overslaan
        if ($array2[$i] == ""){ continue; }
        //key en value pushen aan array merged
        $merged[$array1[$i]] = $array2[$i];
    }
    return $merged;
}
?>
