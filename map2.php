<?
	#
	# $Id$
	#

	include('include/init.txt');


	#
	# how big is our city?
	#

	$extents = array(
		't' => 3,
		'l' => 3,
		'b' => 3,
		'r' => 3,
	);

	$smarty->assign('extents', $extents);


	#
	# output
	#

	$smarty->display('page_map2.txt');
?>