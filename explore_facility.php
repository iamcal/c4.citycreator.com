<?
	# $Id: explore_facility.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM facility_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch industries
	#

	$industries = array();

	$smarty->assign_by_ref('industries', $industries);

	$result = db_query("SELECT * FROM industry_facilities WHERE facility_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[industry_class] = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$row[industry_class_id]"));

		$industries[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_facility.txt');
?>