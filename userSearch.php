<?php
// ini_set('display_errors', 'On');

// Setup the MySQL Connection
require_once('config/mysql.php');

$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if ($_GET['term']) {
	$terms = array();
	$query = "SELECT first_name, last_name, user_id FROM user WHERE first_name LIKE ? OR last_name LIKE ?";
	if(!($stmt = $mysqli->prepare($query))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	$term = '%'.$_GET['term'].'%';
	$stmt->bind_param(ss, $term, $term);
	$stmt->execute();
	$stmt->bind_result($first_name, $last_name, $user_id);

	while($stmt->fetch()) {
			$return_arr[] =  array("label" => $first_name ." ". $last_name,
														 "value" => $user_id);
	}
}

echo json_encode($return_arr);
?>
