<?php 
	//Includes en variabelen.
	$page = "samenvattingbesteladvieslijst.php";
	include("../Includes/Layouts/header.php");
	include("../Includes/db_connect.php");
	
	//Ophalen ingredienten uit database.
	$query = "SELECT * FROM ingredienten";
	$result = mysqli_query($connection, $query);
	if(!$result) {DIE(mysqli_error($connection));}
	
	
	//Kijk of er op de verzend knop gedrukt is.
if (isset($_POST["verzenden"])) {
		$aantalingredientenquery = "(SELECT count(ingredientnr) FROM ingredient "; 
		$aantalingredienten = mysql_fetch_assoc(mysqli_query($aantalingredientenquery));;
		// zoek op in de database hoeveel ingredienten er zijn en zet dat dan in de variabele $aantalingredienten.
		
		// variabelen van inkooporder
		$datum = date ("Y-m-d");
		$totaalbedrag = $totaal_bedrag_alle_producten;
		$statuscode = 1;			
			$leveranciernummerquery = "(SELECT levnr FROM ingredient "; 
			$leveranciernummerquery .= "WHERE ingredientnr = ";
			$leveranciernummerquery .= $ingredientnummer;
			$leveranciernummerquery .= " ); ";
		$leveranciernummer = mysql_fetch_assoc(mysqli_query($leveranciernummerquery));
		//insert van de inkooporder
		$query1 = "INSERT INTO inkooporder(datum, levnr, totaalbedrag, statuscode) 
					VALUES('	{$datum}',
							  	{$leveranciernummer},
							  	{$totaalbedrag},
							  	{$statuscode} );";
		
		$insertinkooporder = mysqli_query($query1);
			if		($insertinkooporder) {}
			else	{DIE(mysqli_error($connection));}
				
	for ($i=0; $i < $aantalingredienten; $i++) { 

		//variabelen

		$besteld = "";	
		$ingredientnummer = $i+1;
			$ingredientnummerquery = "(SELECT ingrednr FROM ingredient"; 
			$ingredientnummerquery .= "WHERE ingredientnr = ";
			$ingredientnummerquery .= $ingredientnummer;
			$ingredientnummerquery .= " ); ";
		$ingredientnummer = mysql_fetch_assoc(mysqli_query($ingredientnummerquery));
		
		//query's		
		//insert van de inkoopregel
		$query2 = "INSERT INTO `eatit`.`inkooporderregel` (`iOrdernr`, `ingrednr`, `besteld`, `geaccepteerd`)
					VALUES('	{$ordernummer}',
							  	{$ingredientnummer},
							  	{$besteld},
							  	{$geaccepteerd} );";
		$insertinkoopregel = mysqli_query($query2);
			if		($insertinkoopregel) {}
			else	{DIE(mysqli_error($connection));}
		
		// updaten van de IB van het ingredient
		$query3 = 	"UPDATE `eatit`.`ingredienten` 
					SET `IB`='(SELECT IB FROM ingredienten WHERE ingredientnr = '$ingredientnummer')
					+{$_SESSION["ingredient$i"]}' 
					WHERE `ingrednr`='{$ingredientnummer}';";
		$update_IB = mysqli_query($query3);
			if		($update_IB) {}
			else	{DIE(mysqli_error($connection));}
}}
?>

<div id="content">
	<h2> Bevestig Bestellijst </h2>

	<table style="width:30%" border="1">
	<form action="">
	<tr>
		<td> <b> Ingredient </b> </td>
		<td> <b> Hoeveelheid Bij Te Bestellen </b> </td>
		<td> <b> Bedrag Per Stuk </b> </td>
		<td> <b> Totaal Bedrag ingredient </b> </td>
	</tr>
<?php
$nummer = 0;
$totaal_bedrag_alle_producten = "";
$prijs_totaal = "";
$aantalingredienten = array();

while ($ingredient = mysqli_fetch_assoc($result))
	if (isset($_POST["ingredienten"])){ $aantalingredienten = count($_POST["ingredienten"]);
 		for ($i=0; $i < $aantalingredienten; $i++) { 
   			if (isset($_POST["inkoopprijs_$nummer"]) && isset($_POST["ingredient_$nummer"]))
  			{$prijs_totaal = $_POST["inkoopprijs_$nummer"]*$_POST["ingredient_$nummer"];}
 
 	{?> <tr><td>  <?php
 	if (isset($_POST["omschrijving_$nummer"])){
 		echo $_POST["omschrijving_$nummer"];}
 	?> </td><td> <?php
 	if (isset($_POST["ingredient_$nummer"])){
 		echo $_POST["ingredient_$nummer"];}
 	?> </td><td> <?php
 	if (isset($_POST["inkoopprijs_$nummer"])){
 		echo $_POST["inkoopprijs_$nummer"];}
 	?> </td><td> <?php
 		echo $prijs_totaal;
$totaal_bedrag_alle_producten = $totaal_bedrag_alle_producten + $prijs_totaal;
$nummer++;}}}
	?> </td></tr></table>

	
	totaal bedrag: &euro; 
	<?php echo $totaal_bedrag_alle_producten ;?>
		<input type="submit" name="verzenden" value="verzenden" />
	</form>
</div>


<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>