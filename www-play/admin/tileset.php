<?
	#
	# $Id$
	#

	include('../include/init.txt');

	loadlib('map');


	#
	# read in vars, or create the default tileset
	#

	if ($_POST[done]){

		$tileset = array(
			'w'	=> intval($_POST[dim_w]),
			'h'	=> intval($_POST[dim_h]),
			'tiles'	=> array(),
		);

		for ($i=1; $i<=$_POST[num]; $i++){

			if ($_POST["tile_{$i}_dim_w"]){

				$tileset[tiles][] = array(

					'x' => $_POST["tile_{$i}_pos_x"],
					'y' => $_POST["tile_{$i}_pos_y"],
					'ox' => $_POST["tile_{$i}_pos_ox"],
					'oy' => $_POST["tile_{$i}_pos_oy"],
					'w' => $_POST["tile_{$i}_dim_w"],
					'h' => $_POST["tile_{$i}_dim_h"],
					'src' => $_POST["tile_{$i}_src"],
				);

			}
		}

		if ($_POST[tile_add_dim_w]){

			$tileset[tiles][] = array(

				'x' => $_POST[tile_add_pos_x],
				'y' => $_POST[tile_add_pos_y],
				'ox' => $_POST[tile_add_pos_ox],
				'oy' => $_POST[tile_add_pos_oy],
				'w' => $_POST[tile_add_dim_w],
				'h' => $_POST[tile_add_dim_h],
				'src' => $_POST[tile_add_src],
			);
		}



	}else if($_POST[code]){


	}else{

		$tileset = array(
			'w'	=> '1',
			'h'	=> '1',
			'tiles'	=> array(),
		);
	}

	$smarty->assign_by_ref('tileset', $tileset);


	#
	# how much extra blue to leave above the tile preview
	#

	$extra_height = 50;


	#
	# assign the $tile.num values, starting from 1
	#

	$c = 0;

	foreach ($tileset[tiles] as $k => $v){

		$c++;
		$tileset[tiles][$k][num] = $c;

		$tileset[tiles][$k][px] = $v[x];
		$tileset[tiles][$k][py] = $v[y] + $extra_height;
	}


	#
	# generate code block
	#

	$code = '';

	$code .= "\$i->set_local_prop('size_x', $tileset[w]);\n";
	$code .= "\$i->set_local_prop('size_y', $tileset[h]);\n";
	$code .= "\n";

	$code .= "\$i->set_tileset(array(\n";
	foreach ($tileset[tiles] as $t){
		$code .= "\tarray($t[x], $t[y], $t[w], $t[h], $t[ox], $t[oy], '$t[src]'),\n";
	}
	$code .= "));\n";

	$smarty->assign('code', $code);


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

	$smarty->display('page_admin_tileset.txt');
?>