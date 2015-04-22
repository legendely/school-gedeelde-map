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

if(isset($_GET['nummer'])){

    $levNr = $_GET['nummer'];
    $query1 = "SELECT * FROM Inkoopfactuur i JOIN Inkooporder io ON io.LevNR = i.InkfNR JOIN Bestelorder bo ON bo.OrderNR = io.OrderNR JOIN Artikelen a ON a.ArtNR = bo.ArtNR WHERE i.InkfNR = $levNr;";
    $result = mysqli_fetch_assoc(mysqli_query($con, $query1));

    if(mysqli_error($con) || empty($result)){
        header('location: index.php?p=inkoopfactuur&res=failed');
    }

    $artNR = $result['ArtNR'];
    $newTV = $result['ART_TechnischeVoorraad'] + $result['Aantal'];
    $newIB = $result['ART_InBestelling'] - $result['Aantal'];

    $query2 = "UPDATE Artikelen SET ART_TechnischeVoorraad=$newTV, ART_InBestelling=$newIB WHERE ArtNR = $artNR;";
    mysqli_query($con, $query2);
    if(mysqli_error($con)){
        header('location: index.php?p=inkoopfactuur&res=failed');
    }

    $query3 = "UPDATE Inkoopfactuur SET Inkf_Status='geleverd' WHERE InkfNR = $levNr;";
    mysqli_query($con, $query3);
	header('location: index.php?p=inkoopfactuur&res=success');
}

$query  = "select * ";
$query .= "from Inkooporder o ";
$query .= "JOIN Inkoopfactuur f ON o.LevNR = f.InkfNR ORDER BY Inkf_Status ASC; ";

$result = mysqli_query($con,$query);
if(!$result){
    header('location: index.php?p=inkoopfactuur&res=failed');
}

while ($row = mysqli_fetch_assoc($result)) {
	echo "Factuurnummer: " . $row['InkfNR'] . "<br/>Factuurstatus: " . $row["Inkf_Status"] . "<br/>" . "Ordernummer: " . $row["OrderNR"] . "<br/>Bedrag: €" . $row["Bedrag"];
	echo "<br/>Ingredientnummer: " . $row["IngNR"] .  "<br/>Aantal: " . $row["Aantal"];

	if($row['Inkf_Status'] == 'besteld'){ 
		echo "<br><br><a href=\"?p=inkoopfactuur&nummer=" . $row["InkfNR"] . "\"> ^ Aftekenen</a><br><br>";
	}

	echo "<hr/>";
}

?>
</div>