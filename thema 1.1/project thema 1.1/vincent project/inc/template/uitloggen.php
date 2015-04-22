<?php
/* Geschreven door:			Thijs Kuilman
 * Studentnummer:			327154
 *
 * Doel van dit bestand:
 * Als de gebruiker op deze pagina komt, dan wordt deze gebruiker uitgelogd.
 */

// De sessie variable me gegevens unsetten. Hiermee wordt de gebruiker uitgelogd.
unset($_SESSION['gegevens']);

// De gebruiker terugsturen naar de index pagina
header ('location: index.php');
?>