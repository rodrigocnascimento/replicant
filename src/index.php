<?php
include_once '../vendor/autoload.php';
include_once 'settings.php';

use \Controllers\BotController;

$documentRoot 	= filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
$httpHost 		= filter_input(INPUT_SERVER, 'HTTP_HOST');
$httpProto 		= filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_PROTO');
$root 			= pathinfo($documentRoot);

define('BASE_FOLDER', basename($root['basename']));
define('APP_URL',    sprintf('%s://%s', $httpProto, $httpHost));
define('APP_ROOT',   $documentRoot);
define('APP_SRC', sprintf('%s/src', $documentRoot));
define('APP_TMP', sprintf('%s/src/tmp', $documentRoot));
define('APP_STATIC_DATA', sprintf('%s/src/staticData', $documentRoot));
define('APP_PUBLIC', sprintf('%s/public', $documentRoot));

function debug($data, $completeBacktrace = false)
{
	$debug_backtrace = debug_backtrace();
	
	$file = sprintf('%s/data.log', APP_TMP);
	
	$_data = print_r($data, true);
	$data = "\n------------------ BEGIN DEBUG ------------------\n";
	$data .= sprintf(
		"\n TIME %s \n FILE %s \n LINE %s \n \n %s \n", 
		date('d/M/Y H:i:s'), 
		$debug_backtrace[0]['file'], 
		$debug_backtrace[0]['line'],
		$_data
	);
	$data .= "\n------------------ END DEBUG ------------------\n";

	if ($completeBacktrace) {
		$data .= "\n------------------ BEGIN BACKTRACE ------------------\n";
		$data .= print_r($debug_backtrace, true);
		$data .= "\n------------------ END BACKTRACE ------------------\n";
	}

	file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
}
// Invoking the controller
$controller = new BotController();
$controller->handle();
