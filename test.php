<?
	include('include/init.txt');

	loadlib('runtime_buildings');


	$state = array();

	echo "<hr />";

	echo serialize($state);

	echo "<hr />";

	runtime_buildings_execute_method(3, &$state, 'on_precreate');

	echo serialize($state);

	echo "<hr />";
?>