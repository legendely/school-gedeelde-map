<div class="content">
	<?php
	//redirect functie wordt gedefineerd
	function redirect_to($new_location)
	{header("location: " . $new_location); 
	exit;}

	//de bestellingsnummers komt in een "gewone" array omdat daar getallen als index gebruikt kunnen worden
	$bestnr = $_SESSION["bestnr"];

	//als er op de vorige pagina op klaar is gedrukt wordt de query uitgevoerd
	//de query zet de bestellingsstatus van alle bestellingen die op de voriga pagina weergegeven zijn op "afgerond"
	if($_POST["done"] ==  "Alles aftekenen"){
		for ($i=0; $i < count($bestnr); $i++) { 
			$query2  = "update Bestelling ";
			$query2 .= "set BEST_Status = 'afgerond' ";
			$query2 .= "where bestNR = $bestnr[$i]; " ;
			$result2 = mysqli_query($con, $query2);

			if(!$result2){
				die("Query mislukt ");
			}
		}
	}
	echo "De bestellingen zijn afgerond. <br/> Ga terug naar de vorige pagina voor nieuwe bestellingen";
	?>
	<!-- deze knop stuurt je terug naar de vorige pagina -->
	<form action"" method="post"><br>
	<input type=submit name="redirect" class="submit" value="Terug" />
	</form>
	<?php
	if(isset($_POST["redirect"])){
		if($_POST["redirect"] = "terug"){
			redirect_to("?p=routeplanning");
		}
	}

	?>
</div>