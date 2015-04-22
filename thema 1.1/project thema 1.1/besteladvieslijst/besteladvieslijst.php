<?php
	$page = "besteladvieslijst.php";
	include("../Includes/db_connect.php");
	include("../Includes/Layouts/header.php");
?>

<?php
$query = "SELECT * FROM ingredienten";
$result = mysqli_query($connection, $query);
if(!$result) {die(mysqli_error($connection));}
//haald alle gegevens uit het tabel ingredienten uit de database
?>
<table style="width:75%" border="1"> 
<form action="samenvattingbestellijst.php"		method="post"
<tr>
	<td>Product</td> <td>Inkoop Prijs</td> <td>Serie Grootte</td> 
	<td>Technische Voorraad</td> <td>Onderweg</td> <td>Gereserveerd</td> 
	<td>Bestel Niveau</td> <td>leverancier nummer</td> <td>Advies Bijbestellen</td>
	<td>Hoeveelheid bij te bestellen.</td>
</tr>
<?php 
	$row = 0;
	$bijbestellen = array();
	while ($ingredient = mysqli_fetch_assoc($result))
		{$i = 0;
			// een loop die alle ingredienten uit de database doorloopt
			foreach ($ingredient as $bijbestellen ) {$economischevoorraad = 0;
				$economischevoorraad = $ingredient["TV"]+$ingredient["IB"]-$ingredient["GR"];
				//berekend de economische voorraad
				if  ($economischevoorraad <= $ingredient["bestelniveau"])
				//als de economische voorraad kleiner of gelijk aan het bestel niveau is, geeft hij $bijbestellen de waarde 1
						{$bijbestellen[$i] = 1;}
				else 	{$bijbestellen[$i] = 0;}
				//zoniet geeft hij $bijbestellen de waarde 0
						$i++;}
			
		?>	<tr><?php  //hieronder worden de database gegevens weergegeven ?>
			<td><?php echo 	$ingredient["omschrijving"] . " " ; ?>		</td>
			<td><?php echo	$ingredient["inkoopprijs"] 	. " " ; ?>		</td>
			<td><?php echo	$ingredient["seriegrootte"] . " " ; ?>		</td>
			<td><?php echo	$ingredient["TV"] 			. " " ; ?>		</td>
			<td><?php echo	$ingredient["IB"] 			. " " ; ?>		</td>
			<td><?php echo	$ingredient["GR"] 			. " " ; ?>		</td>
			<td><?php echo	$ingredient["bestelniveau"] . " " ; ?>		</td>
			<td><?php echo	$ingredient["levnr"] 		. " " ; ?>		</td>
			<td><?php if ($bijbestellen[$row] == 1) {echo "ja";} else {echo "nee";}	; ?> </td>
			<?php //zodra bijbestellen 1 is zal hij het bestel advies ja geven, bij 0 zal hij het bestel advies nee geven. ?>
			<input type="hidden" name="inkoopprijs_<?php echo $row ?>" value="<?php echo $ingredient["inkoopprijs"]?>" />
   			<input type="hidden" name="omschrijving_<?php echo $row ?>" value="<?php echo $ingredient["omschrijving"]?>" />
   			<input type="hidden" name="ingredienten" value="<?php echo $ingredient["omschrijving"]?>" />
			
			<td><input type="input" name="<?php echo "ingredient_$row" ?>" value="" /></td></tr>	
			<?php 
				// zal de ingevoerde waarden naar de session sturen.
				if (isset($_POST["ingredient$row"])){$_POST["ingredient$row"] = $_SESSION["ingredient$row"]; }
					else {$_SESSION["ingredient$row"] = 0;}
			
				
				$row++;} ?>
	</table> <input type="submit" name="bestel" value="bestel" />
</form>			

<?php var_dump($_POST); var_dump($_SESSION); ?>

<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>
?>


