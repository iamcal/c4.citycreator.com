<?
	# $Id$

	include('include/init.txt');

	loadlib('building');


	#
	# TODO: optimize this to not save props to DB unless they changed
	#

	$result = db_query("SELECT * FROM user_buildings");
	while ($row = db_fetch_hash($result)){

		$i = init_building_instance($row);

		$i->b->on_refresh($i);

		$i->save_local_props();
		$i->save_global_props();
	}

	echo "done!";

?>