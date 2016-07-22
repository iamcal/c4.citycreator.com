<?
	# $Id: index.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	if ($cfg[user_ok]){

		#
		# get neighbours
		#

		$smarty->assign('neighbour_count', 0);

		#
		# get city area
		#

		$w = $cfg[user][size_x_pos] + $cfg[user][size_x_neg] + 1;
		$h = $cfg[user][size_y_pos] + $cfg[user][size_y_neg] + 1;

		$smarty->assign('city_area', $w*$h);

		#
		# output
		#

		$smarty->display('page_index_loggedin.txt');
	}else{
		$smarty->display('page_index.txt');
	}
?>