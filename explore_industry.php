<?
	#
	# $Id$
	#

	include('include/init.txt');

	loadlib('industry');
	loadlib('goods');
	loadlib('jobs');


	#
	# fetch main class row
	#

	$o = load_industry($_GET[id]);

	$smarty->assign_by_ref('o', $o);


	#
	# run build event
	#

	$i = new play_industry_instance();
	$o->on_build($i);

	$smarty->assign_by_ref('i', $i);


	#
	# load inputs
	#

	$inputs = array();
	$smarty->assign_by_ref('inputs', $inputs);

	foreach ($i->inputs as $k => $v){

		$x = load_goods(0, $k);
		$x->number = $v;

		$inputs[] = $x;
	}


	#
	# load outputs
	#

	$outputs = array();
	$smarty->assign_by_ref('outputs', $outputs);

	foreach ($i->outputs as $k => $v){

		$x = load_goods(0, $k);
		$x->number = $v;

		$outputs[] = $x;
	}


	#
	# load jobs
	#

	$jobs = array();
	$smarty->assign_by_ref('jobs', $jobs);

	foreach ($i->jobs as $k => $v){

		$x = load_job(0, $k);
		$x->number = $v;

		$jobs[] = $x;
	}


	#
	# show page
	#

	$smarty->display('page_explore_industry.txt');
?>