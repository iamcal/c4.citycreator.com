<?
	#
	# $Id$
	#

	include('../include/init.txt');

	loadlib('building');
	loadlib('map');


	#
	# get building
	#

	$id = intval($_REQUEST[id]);

	$row = db_fetch_hash(db_query("SELECT * FROM class_buildings WHERE id=$id"));

	$smarty->assign_by_ref('building', $row);


	#
	# how much extra blue to leave above the tile previews
	#

	$extra_height = 50;

	$smarty->assign('extra_height', $extra_height);


	#
	# create a building
	#

	$b =& load_building($_REQUEST[id]);
	$i =& new play_building_instance();
	$i->id = 'test';


	#
	# build the building
	#

	$b->on_design($i);

	eval($_POST[custom]); # first time to set build options

	$b->on_build($i);

	eval($_POST[custom]); # second time to apply post-build effects


	#
	# store build process information
	#

	$build_info = array(
		'build_time'	=> $i->props[build_time],
		'build_ticks'	=> $i->props[build_ticks],
		'builders'	=> $i->props[builders],
		'materials'	=> $i->props[materials],
	);

	$smarty->assign_by_ref('build_info', $build_info);


	#
	# store state info once per build tick
	#

	$step_props = array();

	$smarty->assign_by_ref('step_props', $step_props);


	for ($j=0; $j<=$build_info[build_ticks]; $j++){

		$i->props[build_tick] = $j;
		$i->props[built] = $j == $build_info[build_ticks] ? 1 : 0;

		$b->on_refresh($i);

		$step_props[$j] = array(
			'props'		=> $i->props,
			'globals'	=> $cfg[global_props][test],
			'tileset'	=> local_build_tileset($i->props),
		);
	}


	#
	# function to prep a tileset for a given propset
	#

	function local_build_tileset(&$props){

		#
		# grab the tile data
		#

		$tileset = array(
			'w'	=> $props['size_x'],
			'h'	=> $props['size_y'],
			'tiles'	=> $props['tileset'],
		);


		#
		# calculate base tile positions
		#

		$base_tiles = array();

		$bounds = map_bounding_box(0, 0, $tileset[w]-1, $tileset[h]-1);

		$bounds[1] -= $GLOBALS[extra_height];

		for ($x=0; $x<$tileset[w]; $x++){
			for ($y=0; $y<$tileset[h]; $y++){

				$pos = map_tile_position($x, $y);

				$base_tiles[] = array(
					'xp'	=> $pos[0] - $bounds[0],
					'yp'	=> $pos[1] - $bounds[1],
					'x'	=> $x,
					'y'	=> $y,
					'up_x'	=> $pos[0] - $bounds[0],
					'up_y'	=> 0,
					'up_w'	=> 56,
					'up_h'	=> 14 + $pos[1] - $bounds[1],
				);
			}
		}

		$base_w = $bounds[2] - $bounds[0];
		$base_h = $bounds[3] - $bounds[1];

		$tileset[base_tiles] = $base_tiles;
		$tileset[base_w] = $base_w;
		$tileset[base_h] = $base_h;

		return $tileset;
	}



	#
	# output
	#

	$smarty->display('page_admin_building_run.txt');
?>