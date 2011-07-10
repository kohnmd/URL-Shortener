<?php

//*************************************************************
// DIRECTORY PATHS
//*************************************************************
function get_directory_path() {
	return "http://localhost/mdk.im/public_html"; // [CHANGEME]
}

function directory_path() {
	echo get_directory_path();
}


//*************************************************************
// DEBUGGING HELP
//*************************************************************
function pre_print($output, $label="") {
	if($label) echo $label . "\n";
	echo "<pre>";
	print_r($output);
	echo "</pre>";
}

function pre_dump($output, $label="") {
	if($label) echo $label . "\n";
	echo "<pre>";
	var_dump($output);
	echo "</pre>";
}


//**********************************************************************
// IF CLASS FILE HASN'T BEEN INCLUDED, THIS IS A FALLBACK
//**********************************************************************

function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = CLASS_PATH.DS."class_{$class_name}.php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file class_{$class_name}.php could not be found.");
	}
}

?>