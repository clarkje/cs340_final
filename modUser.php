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
$tpl = $mustache->loadTemplate('manageUsers');
$context = array();


require_once('UserStatusQuery.php');
$ustatusQuery = new UserStatusQuery($mysqli);
$context['ustatuses'] = $ustatusQuery->getStatusList();

require_once('UserTypeQuery.php');
$utypeQuery = new UserTypeQuery($mysqli);
$context['utypes'] = $utypeQuery->getTypeList();

require_once('UserQuery.php');
$userQuery = new UserQuery($mysqli);
$context['users'] = $userQuery->getUsers();

if (isset($_REQUEST['action'])) {

	switch($_REQUEST['action']) {
		case "editUser":
			$context['user'] = $userQuery->getUser($_REQUEST['user_id']);
		break;
		case "addUser":
			$error = $userQuery->addUser($_REQUEST['user_first_name'],
							 $_REQUEST['user_last_name'], $_REQUEST['user_email'],
							 $_REQUEST['utype'], $_REQUEST['ustatus']);

		 if(!$error) {
 				$alert = "<div class='alert alert-success' role='alert'>User Updated Successfully</div>";
 			} else {
 				$alert = "<div class='alert alert-danger' role='alert'>User Update Failed</div>";
 			}


		break;
		case "updateUser":
		$error = $userQuery->updateUser($_REQUEST['user_id'],$_REQUEST['user_first_name'],
						 $_REQUEST['user_last_name'], $_REQUEST['user_email'],
						 $_REQUEST['utype'], $_REQUEST['ustatus']);

		if(!$error) {
			$alert = "<div class='alert alert-success' role='alert'>Track Updated Successfully</div>";
		} else {
			$alert = "<div class='alert alert-danger' role='alert'>Track Update Failed</div>";
		}
		break;
		case "deleteUser":

		$error = $userQuery->deleteUser($_REQUEST['user_id']);

		if(!$error) {
			$alert = "<div class='alert alert-success' role='alert'>User Deleted Successfully</div>";
		} else {
			$alert = "<div class='alert alert-danger' role='alert'>User Deleted Failed</div>";
		}
	}
}

if(!isset($_REQUEST['offset']) || $_REQUEST['offset'] < 0) {
	$_REQUEST['offset'] = 0;
}

// Get the number of results
$resultCount = $userQuery->getUserCount();
$context['total_results'] = $resultCount;
$context['offset_begin'] = $_REQUEST['offset'];

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
$context['users'] = $userQuery->getUsers($_REQUEST['offset'],$ITEMS_PER_PAGE);

// Populate the variables to pass to the template
if (isset($alert)) {
	$context['alert'] = $alert;
}

// Since the template engine is brain-dead, we need to tell it which element to
// mark as selected in advance.
if(isset($context['user']['ustatus_id']) && isset($context['ustatuses'])) {
	for ($i = 0; $i < count($context['ustatuses']); $i++) {
		if($context['ustatuses'][$i]['ustatus_id'] == $context['user']['ustatus_id']) {
				$context['ustatuses'][$i]['selected'] = true;
		}
	}
}

// Since the template engine is brain-dead, we need to tell it which element to
// mark as selected in advance.
if(isset($context['user']['utype_id']) && isset($context['utypes'])) {
	for ($i = 0; $i < count($context['utypes']); $i++) {
		if($context['utypes'][$i]['utype_id'] == $context['user']['utype_id']) {
				$context['utypes'][$i]['selected'] = true;
		}
	}
}

echo $tpl->render($context);

// Replacement for intdiv() in PHP 7
// Taken from comments at http://php.net/manual/en/function.intdiv.php
function intdiv($dividend, $divisor) {
	return($dividend - $dividend % $divisor) / $divisor;
}


?>
