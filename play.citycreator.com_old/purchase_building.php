<?
	# $Id: purchase_building.php,v 1.5 2004/09/21 02:47:05 Cal Henderson Exp $

	include('include/init.txt');

	loadlib('runtime');

	login_check_loggedin();


	$id_enc = AddSlashes($HTTP_GET_VARS[id].$HTTP_POST_VARS[id]);
	$building = db_fetch_one("SELECT * FROM buildings WHERE id='$id_enc'");

	if (!$building[id]){
		header("location: /purchase.php");
		exit;
	}

	$smarty->assign_by_ref('building', &$building);


	#
	# load the options
	#

	$options = array();

	$result = db_query("SELECT * FROM building_options WHERE building_id='$building[id]' ORDER BY in_order ASC");
	while($row = db_fetch_array($result)){

		$row[choices] = array();
		$row[selected] = 0;

		$result2 = db_query("SELECT * FROM building_option_choices WHERE option_id='$row[id]' ORDER BY in_order ASC");
		while($row2 = db_fetch_array($result2)){

			if ($row2[cost] > 0){
				$c = number_format($row2[cost]);
				$row2[cost_label] = " <small>[add $c credits]</small>";
			}
			if ($row2[cost] < 0){
				$c = number_format(abs($row2[cost]));
				$row2[cost_label] = " <small>[subtract $c credits]</small>";
			}

			if ($row2['default']){
				$row[selected] = $row2[id];
			}

			$row[choices][$row2[id]] = $row2;

		}

		$options[] = $row;
	}

	$smarty->assign_by_ref('options', &$options);


	#
	# new config?
	#

	if ($HTTP_POST_VARS[config]){

		foreach(array_keys($options) as $k){

			$options[$k][selected] = $HTTP_POST_VARS['option_'.$options[$k][id]];
		}
	}


	#
	# create the building config state
	#

	$state = array();
	$world = array();

	runtime_execute_method('building', $building[id], &$state, &$world, 'on_precreate');
	foreach($options as $option){
		$value = $option[choices][$option[selected]][value];
		runtime_execute_method('building_option', $option[id], &$state, &$world, 'on_create', array($value));
	}
	runtime_execute_method('building', $building[id], &$state, &$world, 'on_postcreate');

	$smarty->assign_by_ref('state', &$state);


	#
	# choose where to place the building?
	#
	# (don't go into the picker unless the user can afford the configured building)
	#

	if ($HTTP_POST_VARS[done] && ($state[cost] <= $cfg[user][cash])){

		$positions = array();

		for($x = -$cfg[user][size_x_neg]; $x <= $cfg[user][size_x_pos]; $x++){
		for($y = -$cfg[user][size_y_neg]; $y <= $cfg[user][size_y_pos]; $y++){
			$positions[] = "{$x}_{$y}";
		}
		}

		$smarty->assign_by_ref('positions', &$positions);


		if ($HTTP_POST_VARS[location]){
			$location = $HTTP_POST_VARS[location];

			if (in_array($location, $positions)){

				list($x,$y) = explode('_', $location);

				db_insert("user_buildings", array(
					'city_id'	=> AddSlashes($cfg[user][id]),
					'building_id'	=> AddSlashes($building[id]),
					'pos_x'		=> AddSlashes($x),
					'pos_y'		=> AddSlashes($y),
					'state'		=> AddSlashes(serialize($state)),
				));

				db_query("UPDATE users SET cash=cash-'$state[cost]' WHERE id='{$cfg[user][id]}'");

				$smarty->display('page_purchase_building_done.txt');
				exit;
			}
		}


		$smarty->display('page_purchase_building_location.txt');
		exit;
	}



	#
	# output
	#

	$smarty->display('page_purchase_building.txt');
?>