<?php
ini_set('display_errors', 'On');
$ITEMS_PER_PAGE = 20;

// Setup the MySQL Connection
require_once('config/mysql.php');

$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Setup the Template Engine
// Adapted from "Using all the options" Mustache Example at:
// https://github.com/bobthecow/mustache.php/wiki
require_once('./lib/mustache/src/Mustache/Autoloader.php');
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
$tpl = $mustache->loadTemplate('manageArtists');
$context = array();

require_once('ArtistQuery.php');
$artistQuery = new ArtistQuery($mysqli);

if (isset($_REQUEST['action'])) {

	switch($_REQUEST['action']) {
		case "editArtist":
			$context['artist'] = $artistQuery->getArtist($_REQUEST['artist_id']);
		break;

		case "deleteArtist":
			$error = $artistQuery->deleteArtist($_REQUEST['artist_id']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Artist Deleted Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Artist Delete Failed</div>";
			}
		break;

		case "updateArtist":
			$error = $artistQuery->updateArtist($_REQUEST['artist_id'], $_REQUEST['artist_name']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Artist Updated Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Artist Update Failed</div>";
			}
		break;
	}
}

if(!isset($_REQUEST['offset']) || $_REQUEST['offset'] < 0) {
	$_REQUEST['offset'] = 0;
}

// Get the number of results
$resultCount = $artistQuery->getArtistCount();
$context['total_results'] = $resultCount;

// If our results extend past a page, setup pagination
// Since mustaches is braindead, we have to figure out all the paging in advance
if($resultCount > $ITEMS_PER_PAGE) {

	$context['offset_begin'] = $_REQUEST['offset'];
	$context['offset_end'] = $_REQUEST['offset'] + $ITEMS_PER_PAGE;

	if (($_REQUEST['offset'] + $ITEMS_PER_PAGE) <= $resultCount) {
		$context['offset_next'] = $_REQUEST['offset'] + $ITEMS_PER_PAGE;
	}

	if (($_REQUEST['offset'] - $ITEMS_PER_PAGE) >= 0) {
		$context['offset_previous'] = $_REQUEST['offset'] - $ITEMS_PER_PAGE;
	}

	$numPages = intdiv($resultCount, $ITEMS_PER_PAGE);

	// Add a page for any remainder...
	if($resultCount % $ITEMS_PER_PAGE > 0) {
		$numPages++;
	}

	$pages = array();
	for ($i = 0; $i < $numPages; $i++) {
		$pageOffset = $i * $ITEMS_PER_PAGE;
		$pages[$i] = array('offset' => $pageOffset,
										 	 'number' => $i + 1);
		if($pageOffset == $_REQUEST['offset']) {
			$pages[$i]['selected'] = true;
		}
	}

	$context['pages'] = $pages;
}

// Populate the form with existing data
$context['artists'] = $artistQuery->getArtists($_REQUEST['offset'],$ITEMS_PER_PAGE);

// Populate the variables to pass to the template
if (isset($alert)) {
	$context['alert'] = $alert;
}

echo $tpl->render($context);

// Replacement for intdiv() in PHP 7
// Taken from comments at http://php.net/manual/en/function.intdiv.php
function intdiv($dividend, $divisor) {
	return($dividend - $dividend % $divisor) / $divisor;
}

?>
