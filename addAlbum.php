<?php
ini_set('display_errors', 'On');


require './lib/mustache/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$mustache = new Mustache_Engine(array(
    'template_class_prefix' => 'templates',
    'cache' => dirname(__FILE__).'tmp/cache',
    'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
    'cache_lambda_templates' => true,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials'),
    'escape' => function($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
    },
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
    'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
));

$tpl = $mustache->loadTemplate('addAlbum');


// $mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
$mysqli = new mysqli("localhost","root","root","clarkje-db");

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Optional alert message that would be displayed above the form
$alert = "";

switch(isset($_POST['action'])) {
  case "addAlbum":
		$query = "INSERT INTO album
								(album_id, artist_id, genre_id, name, release_date, total_tracks)
								VALUES
								(?,?,?,?,?,?)";

		if(!($stmt = $mysqli->prepare($query))){
			echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
		}

		$rdate = $_POST['release_date'].'-01-01';

		$stmt->bind_param("iiissi",$_POST['album_id'],$_POST['artist_id'],
							$_POST['genre'],$_POST['name'],$rdate,
							$_POST['total_tracks']);

		$stmt->execute();

		if(!$mysqli->error) {
			$alert = "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
		} else {
			$alert = "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
		}

		$stmt->close();
  break;
}

$query = "SELECT genre_id, description FROM genre";
if(!($stmt = $mysqli->prepare($query))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

$stmt->execute();
$stmt->bind_result($genre_id, $description);

$i = 0;
$genres = array();
while($stmt->fetch()) {
	$genres[$i] = array("genre_id" => $genre_id,
											"description" => $description);
	$i++;
}

echo $tpl->render(array('genres' => $genres, 'alert' => $alert));
?>
