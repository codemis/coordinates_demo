<?php
/**
 * This file is part of Coordinates Demo.
 * 
 * Coordinates Demo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Coordinates Demo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
/**
 * This file shows how to insert a coordinates array into a string field in PHP.
 * 
 * Database structure:
 * Table:  geo
 * Columns:    id:string
 *             coordinates:string
 *
 * @author Johnathan Pulos
 */
/**
 * Turn off deprecation warnings
 *
 * @author Johnathan Pulos
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
/**
 * include the FileMaker PHP API
 *
 * @author Johnathan Pulos
 */
require_once ('Vendor/FileMaker/FileMaker.php');
/**
 * Get the database settings
 *
 * @author Johnathan Pulos
 */
require('Config/Database.php');
/**
 * Create the FileMaker Instance
 *
 * @author Johnathan Pulos
 */
$fm = new FileMaker($DBConfig['database'], $DBConfig['host'], $DBConfig['user'], $DBConfig['password']);
/**
 * Request all the records
 *
 * @author Johnathan Pulos
 */
$findCommand =& $fm->newFindAllCommand('geo');
$result = $findCommand->execute();
/**
 * Check for an error
 *
 * @author Johnathan Pulos
 */
if (FileMaker::isError($result)) {
    echo "<p>Error: " . $result->getMessage() . "</p>";
    exit;
}
$records = $result->getRecords();
/**
 * Get the last record
 *
 * @var array
 * 
 * @author Johnathan Pulos
 */
$record = end($records);
/**
 * Now setup the coordinates
 *
 * @author Johnathan Pulos
 */
$coordinates =  $record->getField('coordinates');
$unserializedCoordinates = unserialize(base64_decode($coordinates));
/**
 * So let's hand the new coordinates to JQuery to display the coordinates on the page.
 *
 * @author Johnathan Pulos
 */
?>
<html>
    <head>
        <script type="text/javascript" charset="utf-8" src="JS/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8">
						var coordinates = <?php echo json_encode($unserializedCoordinates); ?>;
            $(document).ready(function() {
							// iterate over each coordinate pair
            	$.each(coordinates, function(index, val) {
								// Split each coordinate pair by long and lat
            	  var coords = val.split(',');
								// get the current html from the div#coordinates
								var currentHTML = $('#coordinates').html();
								// add the coordinate pair to the div#coordinates html
								$('#coordinates').html(currentHTML+"Latitude: "+coords[0]+" Longitude: "+coords[1]+"<br>");
            	});
            });
        </script>
    </head>
    <body>
        <h1>Coordinates for Alaska</h1>
        <div id="coordinates"></div>
    </body>
</html>