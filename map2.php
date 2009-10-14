<?
	#
	# $Id$
	#

	include('include/init.txt');


	#
	# how big is our city?
	#

	$extents = array(
		't' => 6,
		'l' => 6,
		'b' => 6,
		'r' => 6,
	);

	$smarty->assign('extents', $extents);


	#
	# output
	#

	$smarty->display('page_map2.txt');
?>