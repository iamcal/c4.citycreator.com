<?
	#
	# $Id$
	#

	include('include/init.txt');

	loadlib('jobs');


	#
	# fetch main class row
	#

	$o = load_job($_GET[id]);

	$smarty->assign_by_ref('o', $o);




	#
	# show page
	#

	$smarty->display('page_explore_job.txt');
?>