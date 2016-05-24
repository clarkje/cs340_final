<?php
// ini_set('display_errors', 'On');

//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if ($_GET['term']) {
	$terms = array();
	$query = "SELECT name, id FROM artist WHERE name LIKE ?";
	if(!($stmt = $mysqli->prepare($query))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	$term = '%'.$_GET['term'].'%';
	$stmt->bind_param(s, $term);
	$stmt->execute();
	$stmt->bind_result($name, $id);

	while($stmt->fetch()) {
			$return_arr[] =  array("label" => $name,
														 "value" => $id
													 );
	}
}

echo json_encode($return_arr);
?>
