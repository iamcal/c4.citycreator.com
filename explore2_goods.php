<?
	#
	# $Id$
	#

	include('include/init.txt');

	loadlib('goods');


	#
	# fetch main class row
	#

	$o = load_goods($_GET[id]);

	$smarty->assign_by_ref('o', $o);





	#
	# show page
	#

	$smarty->display('page_explore2_goods.txt');
?>