<?
	# $Id: explore_building.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM building_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch industries
	#

	$industries = array();

	$smarty->assign_by_ref('industries', $industries);

	$result = db_query("SELECT * FROM building_industries WHERE building_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[industry_class] = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$row[industry_class_id]"));

		$industries[] = $row;
	}


	#
	# fetch residences
	#

	$residences = array();

	$smarty->assign_by_ref('residences', $residences);

	$result = db_query("SELECT * FROM building_residences WHERE building_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[residence_class] = db_fetch_hash(db_query("SELECT * FROM residence_class WHERE id=$row[residence_class_id]"));

		$residences[] = $row;
	}


	#
	# fetch materials
	#

	$materials = array();

	$smarty->assign_by_ref('materials', $materials);

	$result = db_query("SELECT * FROM building_materials WHERE building_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[goods_class] = db_fetch_hash(db_query("SELECT * FROM goods_class WHERE id=$row[goods_class_id]"));

		$materials[] = $row;
	}


	#
	# fetch builders
	#

	$builders = array();

	$smarty->assign_by_ref('builders', $builders);

	$result = db_query("SELECT * FROM building_builders WHERE building_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[job_class] = db_fetch_hash(db_query("SELECT * FROM job_class WHERE id=$row[job_class_id]"));

		$builders[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_building.txt');
?>