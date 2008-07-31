<?
	# $Id: explore_job.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM job_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch industries
	#

	$industries = array();

	$smarty->assign_by_ref('industries', $industries);

	$result = db_query("SELECT * FROM industry_job WHERE job_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[industry_class] = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$row[industry_class_id]"));

		$industries[] = $row;
	}


	#
	# fetch builders
	#

	$builders = array();

	$smarty->assign_by_ref('builders', $builders);

	$result = db_query("SELECT * FROM building_builders WHERE job_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[building_class] = db_fetch_hash(db_query("SELECT * FROM building_class WHERE id=$row[building_class_id]"));

		$builders[] = $row;
	}


	#
	# fetch residences
	#

	$residences = array();

	$smarty->assign_by_ref('residences', $residences);

	$result = db_query("SELECT * FROM job_residences WHERE job_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[residence_class] = db_fetch_hash(db_query("SELECT * FROM residence_class WHERE id=$row[residence_class_id]"));

		$residences[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_job.txt');
?>