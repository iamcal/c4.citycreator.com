<?
	# $Id: map.php 46 2008-01-07 03:35:17Z iamcal $

	include('include/init.txt');

	loadlib('map');
	loadlib('building');

	login_check_loggedin();


	#
	# get map bounds (used for tile offsets)
	#

	$bounds = map_bounding_box(-$GLOBALS[cfg][user][size_x_neg], -$GLOBALS[cfg][user][size_y_neg], $GLOBALS[cfg][user][size_x_pos], $GLOBALS[cfg][user][size_y_pos]);


	#
	# load building instances
	#

	$buildings = array();
	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM user_buildings WHERE user_id={$cfg[user][id]}");
	while ($row = db_fetch_hash($result)){

		$row[i] = init_building_instance($row);

		$tileset = $row[i]->get_local_prop('tileset');

		if (is_array($tileset)){

			$row[tile_bits] = $tileset;
		}else{
			$row[tile_bits] = array(array(20, 3, 16, 16, 0, 0, 'large/unknown.gif'));
		}

		$row[p] = map_tile_position($row[pos_x], $row[pos_y]);

		$size_x = $row[i]->get_local_prop('size_x');
		$size_y = $row[i]->get_local_prop('size_y');

		$row[box] = map_tile_box($row[pos_x], $row[pos_y], $size_x, $size_y, $bounds);

		$row[p][0] -= $bounds[0];
		$row[p][1] -= $bounds[1];

		$buildings[] = $row;
	}


	#
	# sort buildings so we can draw the back ones first
	#

	usort($buildings, 'back_first');

	function back_first($a, $b){

		return ($a[pos_y] == $b[pos_y]) ? 0 : (($a[pos_y] > $b[pos_y]) ? 1 : -1);
	}



	#
	# create blank tiles
	#

	$tiles = array();
	$smarty->assign_by_ref('tiles', $tiles);

	for ($x=-$GLOBALS[cfg][user][size_x_neg]; $x<=$GLOBALS[cfg][user][size_x_pos]; $x++){
		for ($y=-$GLOBALS[cfg][user][size_y_neg]; $y<=$GLOBALS[cfg][user][size_y_pos]; $y++){

			$p = map_tile_position($x, $y);

			$p[0] -= $bounds[0];
			$p[1] -= $bounds[1];

			$tiles[] = array(
				'x' => $x,
				'y' => $y,
				'p' => $p,
			);
		}
	}


	#
	# output
	#

	$smarty->display('page_map.txt');
?>