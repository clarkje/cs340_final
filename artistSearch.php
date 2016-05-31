<?php
// ini_set('display_errors', 'On');

// Setup the MySQL Connection
require_once('config/mysql.php');
$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

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
