<?
	#
	# $Id: build_instance.php 41 2008-01-05 06:08:44Z iamcal $
	#

	include('include/init.txt');

	loadlib('building');
	loadlib('map');

	login_check_loggedin();


	#
	# load building object
	#

	$b =& load_building($_REQUEST[id]);
	$i =& new play_building_instance();

	$b->on_design($i);


	#
	# set any config?
	#

	if ($_POST[done]){

		if ($_POST[props]){

			$i->import_local_props($_POST[props]);

		}else{

			foreach ($b->options as $opt){

				if ($opt['type'] == 'radio'){

					$val = $_POST["radio$opt[key]"];
					$ok = 0;

					foreach ($opt[values] as $k => $v){

						if ($k == $val){ $ok = 1; }
					}

					if ($ok){
						$i->set_local_prop($opt[key], $val);
					}
				}
			}
		}
	}


	#
	# design the object
	#

	$b->on_design($i);
	$b->on_refresh($i);

	$smarty->assign_by_ref('b', $b);
	$smarty->assign_by_ref('i', $i);


	#
	# to show a preview of how it looks, we need to know how much space it'll take up
	#

	$tile_w = $i->get_local_prop('size_x');
	$tile_h = $i->get_local_prop('size_y');

	$bounds = map_bounding_box(1, 1, $tile_w, $tile_h);

	$preview_w = $bounds[2] - $bounds[0];
	$preview_h = $bounds[3] - $bounds[1];

	$smarty->assign('preview_w', $preview_w);
	$smarty->assign('preview_h', $preview_h);


	#
	# overhead is space above the tile used by overlapping pieces
	#

	$min_y = 0;

	foreach ($i->get_local_prop('tileset') as $bit){

		$min_y = min($min_y, $bit[1]);
	}

	$overhead = 0 - $min_y;

	$smarty->assign('overhead', $overhead);
	$smarty->assign('preview_h_full', $preview_h + $overhead);



	#
	# choose a location
	#

	if ($_POST[build]){

		#
		# generate a list of all possible positions first
		#

		$positions = array();
		$smarty->assign_by_ref('positions', $positions);

		for($x = -$cfg[user][size_x_neg]; $x <= $cfg[user][size_x_pos]; $x++){
		for($y = -$cfg[user][size_y_neg]; $y <= $cfg[user][size_y_pos]; $y++){
			$positions["{$x}_{$y}"] = "{$x}_{$y}";
		}
		}


		#
		# exclude ones that are taken
		#

		$result = db_query("SELECT * FROM user_buildings WHERE user_id={$cfg[user][id]}");
		while ($row = db_fetch_hash($result)){

			$key = "{$row[pos_x]}_{$row[pos_y]}";

			unset($positions[$key]);
		}


		#
		# output
		#

		$smarty->display('page_build_instance_location.txt');
		exit;
	}


	#
	# build in a location
	#

	if ($_POST[buildit]){

		#
		# create the building
		#

		list($x, $y) = explode('_', $_POST[location]);

		$id = db_insert('user_buildings', array(

			'user_id'		=> $cfg[user][id],
			'building_template_id'	=> $b->row[id],
			'pos_x'			=> intval($x),
			'pos_y'			=> intval($y),
			'local_props'		=> AddSlashes($i->export_local_props()),
		));


		#
		# run the on_build
		#

		$row = db_fetch_hash(db_query("SELECT * FROM user_buildings WHERE id=$id"));

		$i->id = $id;
		$i->row = $row;

		$b->on_build($i);
		$b->on_refresh($i);

		$i->scrub_design();

		$i->save_local_props();
		$i->save_global_props();


		#
		# all done!
		#

		$smarty->display('page_build_instance_done.txt');
		exit;
	}


	#
	# output
	#

	$smarty->display('page_build_instance.txt');
?>