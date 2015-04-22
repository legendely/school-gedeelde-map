<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	require_once("../Includes/Functions.php");
	$page = "Login";
	include("../Includes/db_connect.php");
	include("../Includes/Layouts/header.php");
	
	if(isset($_SESSION["logged_in"])) {
		header("Location: admin.php");
	}
	
	if(isset($_POST["submit"])) {
		if(empty($_POST["username"]) || empty($_POST["wachtwoord"])) {
			$_SESSION["errors"][] = "Vul uw gebruikersnaam en wachtwoord in.";
		} else {
			$usr = mysqli_real_escape_string($connection, $_POST["username"]);
			$pss = mysqli_real_escape_string($connection, $_POST["wachtwoord"]);
		}
		
		if(empty($_SESSION["errors"])) {
			$query = "SELECT * FROM gebruiker WHERE gebruikersnaam='{$usr}'";
			$result = mysqli_query($connection, $query);
			$gebruiker = mysqli_fetch_assoc($result);
			
			if($gebruiker["wachtwoord"] == $pss) {
				$_SESSION["logged_in"] = TRUE;
				header("Location: admin.php");
			} else {
				$_SESSION["errors"][] = "Wachtwoord incorrect.";
			}
		}
	}
	
?>

<div id="content">
	<h2> Login: </h2>

	<?php
		//Output eventuele foutmeldingen.
		if (!empty($_SESSION["errors"])) {
			echo print_errors($_SESSION["errors"]);
		}
	?>
	
	<form action="" method="POST">
		<table>
			<tr>
				<td> <b> Gebruikersnaam: </b> </td>
				<td> <input type="text" name="username"/>
			</tr>
			
			<tr>
				<td> <b> Wachtwoord: </b> </td>
				<td> <input type="password" name="wachtwoord"/>
			</tr>
			
			<tr>
				<td></td>
				<td> <input type="submit" name="submit" Value="Login"/> </td>
			</tr>
		</table>
	</form>
	
</div>

<?php
	mysqli_close($connection);
		
	//Includes.
	include("../Includes/Layouts/footer.php");
?>