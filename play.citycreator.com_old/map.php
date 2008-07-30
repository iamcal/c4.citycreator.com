<?
	# $Id: map.php,v 1.2 2004/08/21 00:06:57 Cal Henderson Exp $

	include('include/init.txt');

	login_check_loggedin();


	$tile_size = 100;

	#
	# get the total map size
	#

	$w = $tile_size * ($cfg[user][size_x_pos] + $cfg[user][size_x_neg] + 1);
	$h = $tile_size * ($cfg[user][size_y_pos] + $cfg[user][size_y_neg] + 1);


	#
	# calculate all the blank tiles
	#

	$blanks = array();

	for($x = -$cfg[user][size_x_neg]; $x <= $cfg[user][size_x_pos]; $x++){
	for($y = -$cfg[user][size_y_neg]; $y <= $cfg[user][size_y_pos]; $y++){
		$blanks[$x.'_'.$y] = array(
			'x' => ($tile_size * ($x + $cfg[user][size_x_neg]))+1,
			'y' => ($tile_size * ($y + $cfg[user][size_y_neg]))+1,
			'w' => ($tile_size)-2,
			'h' => ($tile_size)-2,
			'pos' => "($x,$y)",
		);
	}
	}


	#
	# calculate all the taken tiles
	#

	$pieces = array();

	$result = db_query("SELECT * FROM user_buildings WHERE city_id='{$cfg[user][id]}'");
	while($row = db_fetch_array($result)){

		$row2 = db_fetch_one("SELECT * FROM buildings WHERE id='$row[building_id]'");

		for($x=$row[pos_x]; $x<$row[pos_x]+$row2[size_x]; $x++){
		for($y=$row[pos_y]; $y<$row[pos_y]+$row2[size_y]; $y++){
			unset($blanks[$x.'_'.$y]);
		}
		}


		if ($row2[size_x] == 1 && $row2[size_y] == 1){
			$pos = "($row[pos_x],$row[pos_y])";
		}else{
			$px2 = $row[pos_x] + $row2[size_x];
			$py2 = $row[pos_y] + $row2[size_y];
			$pos = "($row[pos_x],$row[pos_y])-($px2,$py2)";
		}

		$pieces[] = array(
			'x' => ($tile_size * ($row[pos_x] + $cfg[user][size_x_neg]))+1,
			'y' => ($tile_size * ($row[pos_y] + $cfg[user][size_y_neg]))+1,
			'w' => ($tile_size * ($row2[size_x]))-2,
			'h' => ($tile_size * ($row2[size_y]))-2,
			'pos' => $pos,
			'id' => $row[id],
		);
	}

	$smarty->assign_by_ref('blanks', &$blanks);
	$smarty->assign_by_ref('pieces', &$pieces);
	$smarty->assign('w', $w);
	$smarty->assign('h', $h);

	$smarty->display('page_map.txt');
?>