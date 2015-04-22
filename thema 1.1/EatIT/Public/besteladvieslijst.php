<?php
session_start();
$page = "Admin";
include("../Includes/db_connect.php");
include("../Includes/Layouts/header.php");
?>

<?php
	//haalt alle gegevens uit het tabel ingredienten uit de database
	$query = "SELECT * FROM ingredienten";
	$result = mysqli_query($connection, $query);
	if(!$result) {
		die(mysqli_error($connection));
	}
?>

<div id="content">
	<h2> Besteladvieslijst </h2>

	<table width="90%"> 
	<form action="samenvattingbesteladvieslijst.php" method="POST">
	<tr>
		<td> <b> Product </b> </td> <td> <b> Inkoop Prijs </b> </td> <td> <b> Serie Grootte </b> </td> 
		<td> <b> Technische Voorraad </b> </td> <td> <b> Onderweg </b> </td> <td> <b> Gereserveerd <b> </td> 
		<td> <b> Bestel Niveau </b> </td> <td> <b> leverancier nummer </b> </td> <td> <b> Advies Bijbestellen </b> </td>
		<td> <b> Te Bestellen </b></td>
	</tr>
	<?php 
		$row = 0;
		$besteladvies = array();
		while ($ingredient = mysqli_fetch_assoc($result)) {
			$i = 0;
			// een loop die alle ingredienten uit de database doorloopt
			foreach ($ingredient as $bijbestellen ) {
				$economischevoorraad = 0;
				$economischevoorraad = ($ingredient["TV"]+$ingredient["IB"])-$ingredient["GR"];
				//berekend de economische voorraad
				if  ($economischevoorraad <= $ingredient["bestelniveau"]) {
					//als de economische voorraad kleiner of gelijk aan het bestel niveau is, geeft hij $bijbestellen de waarde 1
					$besteladvies[$i] = 1;
				} else 	{
					//zoniet geeft hij $bijbestellen de waarde 0
					$besteladvies[$i] = 0;
				}
				$i++;
			}
			
			
			?>	<tr><?php  //hieronder worden de database gegevens weergegeven ?>
				<td><?php echo 	$ingredient["omschrijving"] . " " ; ?>		</td>
				<td><?php echo	$ingredient["inkoopprijs"] 	. " " ; ?>		</td>
				<td><?php echo	$ingredient["seriegrootte"] . " " ; ?>		</td>
				<td><?php echo	$ingredient["TV"] 			. " " ; ?>		</td>
				<td><?php echo	$ingredient["IB"] 			. " " ; ?>		</td>
				<td><?php echo	$ingredient["GR"] 			. " " ; ?>		</td>
				<td><?php echo	$ingredient["bestelniveau"] . " " ; ?>		</td>
				<td><?php echo	$ingredient["levnr"] 		. " " ; ?>		</td>
				
				<td> <?php 
					if ($besteladvies[$row] == 1) {
						echo "ja";
					} else { 
						echo "nee";
					}	 
				?> </td>
				
				<?php //zodra bijbestellen 1 is zal hij het bestel advies ja geven, bij 0 zal hij het bestel advies nee geven. ?>
				<input type="hidden" name="inkoopprijs_<?php echo $row ?>" value="<?php echo $ingredient["inkoopprijs"]?>" />
				<input type="hidden" name="omschrijving_<?php echo $row ?>" value="<?php echo $ingredient["omschrijving"]?>" />
				<input type="hidden" name="ingredienten" value="<?php echo $ingredient["omschrijving"]?>" />
				
				<td><input type="text" name="<?php echo "ingredient_$row" ?>" value="" /></td></tr>
				<?php 
				
					// zal de ingevoerde waarden naar de session sturen.
					
					
			$row++;
		} 
				?>
				<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			<tr> <td> <input type="submit" name="bestel" value="Bestel"/> </td> </tr>
		</table> 
	</form>			
</div>

<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>
