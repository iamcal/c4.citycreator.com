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
	# terrain
	#

	$terrain = array();

	$terrain[] = array(
		'x' => 2,
		'y' => 1,
		'type' => 'grass',
	);

	$smarty->assign('terrain', $terrain);


	#
	# output
	#

	$smarty->display('page_map2.txt');
?>