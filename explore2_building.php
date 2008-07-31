<?
	# $Id$

	include('include/init.txt');

	loadlib('building');
	loadlib('industry');
	loadlib('goods');
	loadlib('jobs');


	#
	# fetch main class row
	#

	$b = load_building($_GET[id]);

	$smarty->assign_by_ref('b', $b);


	#
	# run build event
	#

	$i = new play_building_instance();
	$b->on_build($i);

	$smarty->assign_by_ref('i', $i);


	#
	# load industries
	#

	$industries = array();
	$smarty->assign_by_ref('industries', $industries);

	foreach ($i->industries as $k => $v){

		$in = load_industry(0, $k);
		$in->number = $v;

		$industries[] = $in;
	}


	#
	# load building goods
	#

	$build_goods = array();
	$smarty->assign_by_ref('build_goods', $build_goods);

	foreach ($i->build_goods as $k => $v){

		$gd = load_goods(0, $k);
		$gd->number = $v;

		$build_goods[] = $gd;
	}


	#
	# load building workers
	#

	$build_people = array();
	$smarty->assign_by_ref('build_people', $build_people);

	foreach ($i->build_people as $k => $v){

		$pl = load_job(0, $k);
		$pl->number = $v;

		$build_people[] = $pl;
	}


	#
	# show page
	#

	$smarty->display('page_explore2_building.txt');
?>