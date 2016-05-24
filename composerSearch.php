<?php
// ini_set('display_errors', 'On');

//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if ($_GET['term']) {
	$terms = array();
	$query = "SELECT first_name, last_name, composer_id FROM composer WHERE first_name LIKE ? OR last_name LIKE ?";
	if(!($stmt = $mysqli->prepare($query))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	$term = '%'.$_GET['term'].'%';
	$stmt->bind_param(ss, $term, $term);
	$stmt->execute();
	$stmt->bind_result($first_name, $last_name, $composer_id);

	while($stmt->fetch()) {
			$return_arr[] =  array("label" => $first_name ." ". $last_name,
														 "value" => $composer_id);
	}
}

echo json_encode($return_arr);
?>
