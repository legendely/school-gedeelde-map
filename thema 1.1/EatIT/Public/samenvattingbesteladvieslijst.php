<?php 
	session_start();
	//Includes en variabelen.
	$page = "samenvattingbesteladvieslijst.php";
	include("../Includes/Layouts/header.php");
	include("../Includes/db_connect.php");
	
	//Ophalen ingredienten uit database.
	$query = "SELECT * FROM ingredienten";
	$result = mysqli_query($connection, $query);
	if(!$result) {
		DIE(mysqli_error($connection));
	}
	
	$_SESSION["ingredienten"] = $_POST["ingredienten"];
	
	//Kijk of er op de verzend knop gedrukt is.
	if (isset($_POST["verzenden"])) {
		$aantalingredienten = count($_SESSION["ingredienten"]);
		
		for ($i=0; $i < $aantalingredienten; $i++) { 
			$nummer = 0;
			
			$lev_ingrednrquery = "SELECT * FROM ingredienten 
								  WHERE omschrijving={$ingredient["omschrijving"]};";
			$lev_ingred_result = mysqli_query($connection, $lev_ingrednrquery);
			$lev_ingrednr = mysqli_fetch_assoc($lev_ingred_result);
			
			/* Je selecteert beide dingen uit dezelfde tabel, beter in 1 query
			
			$leveranciernummerquery = "(SELECT levnr FROM ingredient"; 
			$leveranciernummerquery .= "WHERE omschrijving = ";
			$leveranciernummerquery .= $ingredient["omschrijving"];
			$leveranciernummerquery .= " ); ";
			$leveranciernummer = mysql_fetch_assoc(mysqli_query($leveranciernummerquery));
				
				
			$ingredientnummerquery = "(SELECT ingrednr FROM ingredient"; 
			$ingredientnummerquery .= "WHERE omschrijving = ";
			$ingredientnummerquery .= $ingredient["omschrijving"];
			$ingredientnummerquery .= " ); ";
			$ingredientnummer = mysql_fetch_assoc(mysqli_query($ingredientnummerquery));*/
			
			$datum = date ("Y-m-d");
			$totaalbedrag = $totaal_bedrag_alle_producten;
			$statuscode = 1;
			$besteld = "";

			if (isset($_POST["ingredient_$nummer"])) {
				$besteld = $_POST["ingredient_$nummer"];
			}
			$geaccepteerd = 0;
			
			$query1 = "INSERT INTO inkooporder(datum, levnr, totaalbedrag, statuscode) 
					   VALUES('{$datum}',
							  {$lev_ingrednr["levnr"]},
							  {$totaalbedrag},
							  {$statuscode} );";
				
			/*$query1 = "INSERT INTO inkooporder (datum, levnr, totaalbedrag, statuscode) ";
			$query1 .= "VALUES (";
			$query1 .= "'";
			$query1 .= $datum; //datum
			$query1 .= "', '";
			$query1 .= $lev_ingrednr["levnr"]; //leverancier nummer
			$query1 .= "', '";
			$query1 .= $totaalbedrag;
			$query1 .= "', '";
			$query1 .= $statuscode;
			$query1 .= "');";*/
				
			$insertinkooporder = mysqli_query($query1);
			if($insertinkooporder) {
				
			} else {
				DIE(mysqli_error($connection));
			}

			/* Tijdelijk uitgeschakeld.
			$ordernummer = mysqli_insert_id($connection);

			$query2 = "INSERT INTO `eatit`.`inkooporderregel` (`iOrdernr`, `ingrednr`, `besteld`, `geaccepteerd`) ";
			$query2 .= "VALUES (";
			$query2 .= "'";
			$query2 .= $ordernummer;//ordernummer
			$query2 .= "', '";
			$query2 .= $ingredientnummer["ingrednr"]; //ingredientnummer
			$query2 .= "', '";
			$query2 .= $besteld; //besteld
			$query2 .= "', '";
			$query2 .= $geaccepteerd;//geaccepteerd
			$query2 .= "');";
			
			$insertinkoopregel = mysqli_query($query2);*/
					
			$nummer++;
			}
		}
?>

<div id="content">
	<h2> Bevestig Bestellijst </h2>

	<table style="width:30%" border="1">
	<form action="samenvattingbesteladvieslijst.php">
	<?php// hieronder de query om het in de database te zetten.
	$nummer = 0;

	?>
	<tr>
		<td> <b> Ingredient </b> </td>
		<td> <b> Hoeveelheid Bij Te Bestellen </b> </td>
		<td> <b> Bedrag Per Stuk </b> </td>
		<td> <b> Totaal Bedrag ingredient </b> </td>
	</tr>
	<?php
	$totaal_bedrag_alle_producten = "";
	$prijs_totaal = "";
	$aantalingredienten = array();

	while ($ingredient = mysqli_fetch_assoc($result)) {
	if (!empty($_POST["ingredienten"])) { 
		$aantalingredienten = count($_POST["ingredienten"]);
		for ($i=0; $i < $aantalingredienten; $i++) { 
			if (isset($_POST["inkoopprijs_$nummer"]) && isset($_POST["ingredient_$nummer"])) {
				$prijs_totaal = $_POST["inkoopprijs_$nummer"]*$_POST["ingredient_$nummer"];
			}
		
		{?> <tr><td>  <?php
		if (isset($_POST["omschrijving_$nummer"])) {
			echo $_POST["omschrijving_$nummer"];
		}
		?> </td><td> <?php
		if (isset($_POST["ingredient_$nummer"])) {
			echo $_POST["ingredient_$nummer"];
		}
		
		?> </td><td> <?php
		if (isset($_POST["inkoopprijs_$nummer"])) {
			echo $_POST["inkoopprijs_$nummer"];
		}
		?> </td><td> <?php
		echo $prijs_totaal;
		
		$totaal_bedrag_alle_producten = $totaal_bedrag_alle_producten + $prijs_totaal;
		
		$nummer++;}}}
	}
	?> </td></tr></table>

	totaal bedrag: &euro; 
	<?php echo $totaal_bedrag_alle_producten ;
	//var_dump($_POST);
	var_dump($_POST["ingredienten"]) . "<br/>";
	var_dump($_SESSION);?>
	
		<input type="submit" name="verzenden" value="verzenden" />
	</form>
</div>


<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>