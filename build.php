<?
	#
	# $Id: build.php 24 2007-12-24 04:47:24Z iamcal $
	#

	include('include/init.txt');

	loadlib('building');

	login_check_loggedin();


	#
	# load building objects
	#

	$buildings = array();
	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM class_buildings");
	while($row = db_fetch_array($result)){

		$b =& new play_building();
		$b->row = $row;

		$i =& new play_building_instance();
		$i->b = $b;

		$b->on_design($i);

		$buildings[] =& $i;
	}


	#
	# output
	#

	$smarty->display('page_build.txt');
?>