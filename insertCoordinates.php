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
 * The coordinates string holding the boundaries of alaska.  Each coordinate pair (lat,long) is separated with a space
 *
 * @var string
 **/
$alaskaBoundries = "70.0187,-141.0205 70.1292,-141.7291 70.4515,-144.8163 70.7471,-148.4583 70.7923,-151.1609 71.1470,-152.6221 71.1185,-153.9954 71.4307,-154.8853 71.5232,-156.7529 71.2796,-157.9449 71.2249,-159.6313 70.6363,-161.8671 70.0843,-163.5809 69.3028,-165.2399 69.1782,-166.8768 68.3344,-168.0414 67.6844,-165.9155 67.2933,-164.6082 66.7789,-164.0149 66.5810,-165.7507 66.2867,-167.5745 66.0269,-168.9862 65.4970,-168.9478 65.0420,-167.4756 64.3922,-167.0142 64.0554,-165.7343 64.0193,-163.2294 63.9615,-162.1143 63.6877,-163.6029 63.4530,-165.3717 62.4133,-166.3715 61.6534,-166.9867 60.8556,-166.4429 60.5357,-167.8381 59.5482,-167.7118 59.4115,-165.8002 59.3696,-164.5972 59.1168,-162.8558 58.1185,-162.5427 58.1359,-160.6421 58.0285,-159.5050 57.6336,-158.8953 56.9090,-159.9060 56.3926,-160.6531 56.2342,-161.8835 55.7240,-162.9822 55.2478,-164.3994 54.7753,-165.3168 54.1463,-167.1075 53.5632,-168.5852 53.1402,-169.9146 52.5964,-169.5959 52.9089,-168.2227 54.2139,-162.7734 54.6786,-159.1452 55.6567,-155.4634 57.3510,-152.1400 59.2209,-150.8203 59.7695,-147.4461 60.3521,-145.9850 59.8917,-144.1544 59.8172,-141.6811 59.5225,-140.5124 59.0292,-138.8548 57.9032,-136.8526 56.9157,-136.0725 56.1555,-134.9794 55.3237,-134.0057 54.6341,-133.6418 54.7135,-130.6261 55.2869,-129.9930 55.9869,-130.0108 56.1057,-130.1083 56.6086,-131.5887 57.8404,-132.8755 58.7276,-133.8423 59.3108,-134.9121 59.8020,-135.4724 59.6039,-136.3445 59.1619,-136.8251 59.2441,-137.6079 60.0902,-139.2119 60.3575,-139.0938 60.1866,-140.0056 60.3059,-140.9999 70.0187,-141.0205";
/**
 * The array for each of the coordinates in the $alaskaBoundries
 *
 * @var array
 **/
$alaskaBoundriesArray = explode(' ', $alaskaBoundries);
/**
 * Prepare the coordinates for the database by serializing and encoding the data
 *
 * @var string
 **/
$preparedAlaskaBoundries = base64_encode(serialize($alaskaBoundriesArray));
/**
 * The data to be saved in the database
 *
 * @var array
 **/
$newGeo = array('id' => uniqid(), 'coordinates' => $preparedAlaskaBoundries);
/**
 * Save the new data
 *
 * @author Johnathan Pulos
 */
$newRequest =& $fm->newAddCommand('geo', $newGeo);
$result = $newRequest->execute();

//check for an error
if (FileMaker::isError($result)) {
    echo "Error: " . $result->getMessage();
    exit;
}
/**
 * Let's look at the data
 *
 * @author Johnathan Pulos
 */
$records = $result->getRecords();
$record = $records[0];
$savedCoordinates = $record->getField('coordinates');
/**
 * We need to decode using base64 then unserialized the data
 *
 * @author Johnathan Pulos
 */
print_r(unserialize(base64_decode($savedCoordinates)));
