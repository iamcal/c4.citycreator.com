<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get buildings
	#

	$buildings = array();
	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM class_buildings");
	while ($row = db_fetch_hash($result)){

		$buildings[] = $row;
	}


	#
	# output
	#

	$smarty->display('page_admin_buildings.txt');
?>