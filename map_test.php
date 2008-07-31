<?
	#
	# $Id$
	#

	include('include/init.txt');

	loadlib('map');
	loadlib('building');

	#
	# load building instances
	#

	$buildings = array();

	$result = db_query("SELECT * FROM user_buildings WHERE user_id=1");
	while ($row = db_fetch_hash($result)){

		$row[i] = init_building_instance($row);

		$tileset = $row[i]->get_local_prop('tileset');

		if (is_array($tileset)){

			$row[tile_bits] = $tileset;
		}else{
			$row[tile_bits] = array(array(20, 3, 16, 16, 0, 0, 'large/unknown.gif'));
		}

		$buildings[] = $row;
	}



	$map = map_init(-3, -3, 3, 3, $buildings);

	$smarty->assign_by_ref('map', $map);


	#
	# output
	#

	$smarty->display('page_map_test.txt');
?>