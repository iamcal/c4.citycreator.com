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
	# run build process
	#

	$b =& load_building($_REQUEST[id]);
	$i =& new play_building_instance();

	$b->on_design($i);

	eval($_POST[custom]); # first time to set build options

	$b->on_build($i);

	eval($_POST[custom]); # second time to apply post-build effects

	$b->on_refresh($i);

	$smarty->assign_by_ref('instance', $i);


	#
	# how much extra blue to leave above the tile preview
	#

	$extra_height = 50;

	$smarty->assign('extra_height', $extra_height);


	#
	# grab the tile data
	#

	$tileset = array(
		'w'	=> $i->get_local_prop('size_x'),
		'h'	=> $i->get_local_prop('size_y'),
		'tiles'	=> $i->get_local_prop('tileset'),
	);

	$smarty->assign_by_ref('tileset', $tileset);


	#
	# calculate base tile positions
	#

	$base_tiles = array();

	$bounds = map_bounding_box(0, 0, $tileset[w]-1, $tileset[h]-1);

	$bounds[1] -= $extra_height;

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

	$smarty->assign('base_tiles', $base_tiles);
	$smarty->assign('base_w', $base_w);
	$smarty->assign('base_h', $base_h);



	#
	# output
	#

	$smarty->display('page_admin_building_run.txt');
?>