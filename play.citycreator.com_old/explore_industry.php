<?
	# $Id: explore_industry.php,v 1.2 2006/03/17 05:36:42 cal Exp $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch inputs/outputs
	#

	$inputs = array();
	$outputs = array();

	$smarty->assign_by_ref('inputs', $inputs);
	$smarty->assign_by_ref('outputs', $outputs);

	$result = db_query("SELECT * FROM industry_input WHERE industry_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[goods_class] = db_fetch_hash(db_query("SELECT * FROM goods_class WHERE id=$row[goods_class_id]"));

		$inputs[] = $row;
	}

	$result = db_query("SELECT * FROM industry_output WHERE industry_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[goods_class] = db_fetch_hash(db_query("SELECT * FROM goods_class WHERE id=$row[goods_class_id]"));

		$outputs[] = $row;
	}


	#
	# fetch buildings
	#

	$buildings = array();

	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM building_industries WHERE industry_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[building_class] = db_fetch_hash(db_query("SELECT * FROM building_class WHERE id=$row[building_class_id]"));

		$buildings[] = $row;
	}


	#
	# fetch jobs
	#

	$jobs = array();

	$smarty->assign_by_ref('jobs', $jobs);

	$result = db_query("SELECT * FROM industry_job WHERE industry_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[job_class] = db_fetch_hash(db_query("SELECT * FROM job_class WHERE id=$row[job_class_id]"));

		$jobs[] = $row;
	}


	#
	# fetch facilities
	#

	$facilities = array();

	$smarty->assign_by_ref('facilities', $facilities);

	$result = db_query("SELECT * FROM industry_facilities WHERE industry_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[facility_class] = db_fetch_hash(db_query("SELECT * FROM facility_class WHERE id=$row[facility_class_id]"));

		$facilities[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_industry.txt');
?>