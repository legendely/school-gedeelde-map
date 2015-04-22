<?php
/**
 * Created by PhpStorm.
 * User: Sergen Nurel
 * Date: 5-11-2014
 * Time: 23:21
 */

    //Wordt gekeken of alles is ingevoerd en of alles correct is igevoerd.
    if (isset($_POST['ink_submit'])) {
        //Alle $_POST data wordt in variabelen gezet
        $artnr = $_POST['ArtNR'];
        $aantal = $_POST['Aantal'];

        $query0 = "SELECT ART_Prijs FROM Artikelen WHERE ArtNR = $artnr;";
        $totaalbedrag = mysqli_fetch_assoc(mysqli_query($con, $query0))['ART_Prijs'] * $aantal;
        if(mysqli_error($con)){
            header("location: index.php?p=inkooporder&res=failed");
        }

        $query = "INSERT INTO Inkoopfactuur (Inkf_Status, Bedrag) VALUES ('besteld', $totaalbedrag);";
        mysqli_query($con, $query);
        if(mysqli_error($con)){
            header("location: index.php?p=inkooporder&res=failed");
        }

        $insid = mysqli_insert_id($con);
        $query1 = "INSERT INTO Inkooporder (LevNR, Aantal, IngNR) VALUES ($insid, $aantal, $artnr)";
        mysqli_query($con, $query1);
        if(mysqli_error($con)){
            header("location: index.php?p=inkooporder&res=failed");
        }

        $ordernr = mysqli_insert_id($con);
        $query2 = "INSERT INTO Bestelorder (ArtNR, OrderNR, Aantal) VALUES ($artnr, $ordernr, $aantal);";
        mysqli_query($con, $query2);
        if(mysqli_error($con)){
            header("location: index.php?p=inkooporder&res=failed");
        }

        header("location: index.php?p=inkooporder&res=success&id=$ordernr");


       /* //Query voor toevoegen ingevulde waardes.
        $query = "INSERT INTO Bestelorder (ArtNR, OrderNR, Aantal)
                  VALUES ($artnr, $ordernr, $aantal);";
        $result = mysqli_query($con, $query);

        if (!$result) {
            header("location: index.php?p=inkooporder&res=failed");
        }

        $query3  = "select ART_Prijs from Artikelen where ArtNR = $artnr;";
        $result3 = mysqli_fetch_assoc(mysqli_query($con, $query3));


        $prijs =  $result3['ART_Prijs'] * $aantal;
        $query2  = "insert into Inkoopfactuur (InkfNR, Inkf_Status, Bedrag)
                    values ($ordernr, 'verwerken', $prijs);";
        $result2 = mysqli_query($con, $query2);

        // Kijken hoeveel er momenteel gereserveerd zijn van dit artikel
        $query4  = "select ART_Gereserveerd from Artikelen where ArtNR = $artnr;";
        $result4 = mysqli_fetch_assoc(mysqli_query($con, $query4));

        $aantal_gereserveerd = $result4['ART_Gereserveerd'];
        $n_aantal_gereserveerd = $aantal_gereserveerd + $aantal;

        // Bereken de nieuwe waarde voor ART_gereserveerd
        $query5 = "UPDATE Artikelen SET ART_Gereserveerd = " . $n_aantal_gereserveerd . " WHERE ArtNR =" . $artnr;
        $result5 = mysqli_query($con, $query5);

        echo $query5;

        if(!$result2){
            header("location: index.php?p=inkooporder&res=failed");
        }
        header("location: index.php?p=inkooporder&res=success");*/
    }
?>