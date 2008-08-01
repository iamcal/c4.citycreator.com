<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get industries
	#

	$buildings = array();
	$smarty->assign_by_ref('industries', $industries);

	$result = db_query("SELECT * FROM class_industries");
	while ($row = db_fetch_hash($result)){

		$industries[] = $row;
	}


	#
	# output
	#

	$smarty->display('page_admin_industries.txt');
?>