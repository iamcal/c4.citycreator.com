<?
	# $Id: index.php,v 1.2 2004/07/09 02:21:33 Cal Henderson Exp $

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