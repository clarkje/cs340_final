<?php
// ini_set('display_errors', 'On');

//Connects to the database
//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
$mysqli = new mysqli("localhost","root","root","clarkje-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if (isset($_GET['term'])) {
	$terms = array();
	$query = "SELECT name, artist_id FROM artist WHERE name LIKE ?";

	if(!($stmt = $mysqli->prepare($query))){
		var_dump($mysqli->error);
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	$stmt->bind_param(s, $term);

	$term = '%'.$_GET['term'].'%';
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($name, $id);

	while($stmt->fetch()) {
			$return_arr[] =  array("label" => $name,
														 "value" => $id
													 );
	}
}

echo json_encode($return_arr);
?>
