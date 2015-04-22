<?php 
$page = "Admin";
include("../Includes/Layouts/header.php"); 


?>

<div id="content"> <form action = "" method = "post">
<select name = "ordernummer">
<?php
	//Vebinden met database 
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "EatIT2";
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	//Verbinding succesvol?
	if (mysqli_connect_errno()) {
		DIE("Verbinding mislukt: " . mysqli_connect_error());
	}
	
$query1 = "SELECT vOrdernr FROM verkooporder ORDER BY aflevertijd ASC";
$resultaat1 = mysqli_query($connection, $query1);
		if (!$resultaat1) {
			DIE("Query mislukt." . mysqli_error($connection));
		}
while ($nummer = mysqli_fetch_assoc($resultaat1)){
	echo "<option value=". $nummer["vOrdernr"].">" . $nummer["vOrdernr"] . "</option>";
	
	
}

?>
</select> 	

<input type = "submit" name = "verander" value = "Verander status in afgeleverd">  	
<input type = "submit" name = "verandert" value = "Verander status terug in klaar">	
	
	
</form>

<h><b>Bestellingen klaar:</b></h>
</br></br>


<?php
	
	
	
	
	
	$query = "SELECT afleveradres, aflevertijd, vOrdernr FROM verkooporder WHERE statuscode = 2 ORDER BY aflevertijd ASC";
	$resultaat = mysqli_query($connection, $query);
		if (!$resultaat) {
			DIE("Query mislukt." . mysqli_error($connection));
		}
		else {
			echo "<table>";	
			while ($route = mysqli_fetch_assoc($resultaat)){
				echo "<tr>";
				echo "<td>" . "Verkoop Ordernr" . "</td>";
				echo "<td>" . "=" . " " .$route["vOrdernr"]. "</td>";
				echo "<tr><td></td></tr>";
				echo "<td>" . "Afleveradres " . "</td>";
				echo "<td>" . "=" . " " .$route["afleveradres"]. "</td>";
				echo "<tr><td></td></tr>";
				echo "<td>" . "Aflevertijd" . "</td>";
				echo "<td>" . "=" . " " . $route["aflevertijd"]  . "</td>" ;
				echo "<tr><td>&nbsp;</td></tr>";
				echo "</tr>"; 
			}
			echo "</table>";
		}
		
		if(isset($_POST["verander"])){
	$query2 = "UPDATE verkooporder SET statuscode = 3 where vOrdernr = " . $_POST["ordernummer"];
	$resultaat2 = mysqli_query($connection, $query2);
		if (!$resultaat2) {
			DIE("Query mislukt." . mysqli_error($connection));	
			
		}
		header("Location: Routepage.php");
	
	}
	
		if(isset($_POST["verandert"])){
	$query3 = "UPDATE verkooporder SET statuscode = 2 where vOrdernr = " . $_POST["ordernummer"];
	$resultaat3 = mysqli_query($connection, $query3);
		if (!$resultaat3) {
			DIE("Query mislukt." . mysqli_error($connection));	
			
		}
		header("Location: Routepage.php");
	}
	
		
	
	?>	
	
	<hr>
	<hr>
	<h><b>Afgeleverde bestellingen:</b></h>
	</br></br>
	
	
	<?php	
		$query4 = "SELECT afleveradres, aflevertijd, vOrdernr FROM verkooporder WHERE statuscode = 3 ORDER BY aflevertijd ASC";
	$resultaat4 = mysqli_query($connection, $query4);
		if (!$resultaat4) {
			DIE("Query mislukt." . mysqli_error($connection));
		}
		else {
			echo "<table>";	
			while ($routeklaar = mysqli_fetch_assoc($resultaat4)){
				echo "<tr>";
				echo "<td>" . "Verkoop Ordernr" . "</td>";
				echo "<td>" . "=" . " " .$routeklaar["vOrdernr"]. "</td>";
				echo "<tr><td></td></tr>";
				echo "<td>" . "Afleveradres " . "</td>";
				echo "<td>" . "=" . " " .$routeklaar["afleveradres"]. "</td>";
				echo "<tr><td></td></tr>";
				echo "<td>" . "Aflevertijd" . "</td>";
				echo "<td>" . "=" . " " . $routeklaar["aflevertijd"]  . "</td>" ;
				echo "<tr><td>&nbsp;</td></tr>";
				echo "</tr>"; 
			}
			echo "</table>";
		}
		
		

	?>
</div>
<?php
	include("../Includes/Layouts/footer.php");
?>