<?
	# $Id: explore_goods.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch main class row
	#

	$id = intval($_GET[id]);

	$class = db_fetch_hash(db_query("SELECT * FROM goods_class WHERE id=$id"));

	$smarty->assign_by_ref('class', $class);


	#
	# fetch industries
	#

	$industries_in = array();
	$industries_out = array();

	$smarty->assign_by_ref('industries_in', $industries_in);
	$smarty->assign_by_ref('industries_out', $industries_out);

	$result = db_query("SELECT * FROM industry_input WHERE goods_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[industry_class] = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$row[industry_class_id]"));

		$industries_in[] = $row;
	}

	$result = db_query("SELECT * FROM industry_output WHERE goods_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[industry_class] = db_fetch_hash(db_query("SELECT * FROM industry_class WHERE id=$row[industry_class_id]"));

		$industries_out[] = $row;
	}


	#
	# fetch buildings
	#

	$buildings = array();

	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM building_materials WHERE goods_class_id=$class[id]");
	while ($row = db_fetch_hash($result)){

		$row[building_class] = db_fetch_hash(db_query("SELECT * FROM building_class WHERE id=$row[building_class_id]"));

		$buildings[] = $row;
	}


	#
	# show page
	#

	$smarty->display('page_explore_goods.txt');
?>