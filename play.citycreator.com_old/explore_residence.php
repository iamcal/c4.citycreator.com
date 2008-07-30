<?
	# $Id: explore_residence.php,v 1.1 2006/03/17 05:16:36 cal Exp $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM residence_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch buildings
	#

	$buildings = array();

	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM building_residences WHERE residence_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[building_class] = db_fetch_hash(db_query("SELECT * FROM building_class WHERE id=$row[building_class_id]"));

		$buildings[] = $row;
	}


	#
	# fetch workers
	#

	$workers = array();

	$smarty->assign_by_ref('workers', $workers);

	$result = db_query("SELECT * FROM job_residences WHERE residence_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[job_class] = db_fetch_hash(db_query("SELECT * FROM job_class WHERE id=$row[job_class_id]"));

		$workers[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_residence.txt');
?>