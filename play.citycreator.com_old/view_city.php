<?
	# $Id: view_city.php,v 1.2 2004/06/30 20:37:43 Cal Henderson Exp $

	include('include/init.txt');

	$city_id = AddSlashes($HTTP_GET_VARS[id]);

	$city_row = db_fetch_one("SELECT * FROM users WHERE id='$city_id'");

	$w = 75 * ($city_row[size_x_pos] + $city_row[size_x_neg] + 1);
	$h = 75 * ($city_row[size_y_pos] + $city_row[size_y_neg] + 1);
	$pieces = array();

	$result = db_query("SELECT * FROM user_buildings WHERE city_id='$city_row[id]'");
	while($row = db_fetch_array($result)){

		$row2 = db_fetch_one("SELECT * FROM buildings WHERE id='$row[building_id]'");

		$pieces[] = array(
			'x' => (75 * ($row[pos_x] + $city_row[size_x_neg]))+1,
			'y' => (75 * ($row[pos_y] + $city_row[size_y_neg]))+1,
			'w' => (75 * ($row2[size_x]))-2,
			'h' => (75 * ($row2[size_y]))-2,
		);
	}

?>

<div style="position: relative; width: <?=$w?>px; height: <?=$h?>px; background: url('images/map_tile.gif') repeat-xy;">
<? foreach($pieces as $piece){ ?>
	<div style="position: absolute; left: <?=$piece[x]?>px; top: <?=$piece[y]?>px; width: <?=$piece[w]?>px; height: <?=$piece[h]?>px; background-color: silver;"></div>
<? } ?>
</div>
