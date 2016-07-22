<?
	# $Id$

	include('include/init.txt');

	loadlib('building');


	$time = time();

	$result = db_query("SELECT * FROM events WHERE date_fire < $time");
	while ($row = db_fetch_hash($result)){

		$i = load_building_instance($row[building_instance_id]);

		$i->event = $row[event];
		$i->b->on_event($i);
		$i->b->on_refresh($i);

		$i->save_local_props();
		$i->save_global_props();

		echo "processed event $row[id]!<br />\n";
	}

	echo "done!";

?>