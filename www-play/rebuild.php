<?
	# $Id$

	include('include/init.txt');

	loadlib('building');


	#
	# TODO: optimize this to not save props to DB unless they changed
	#


	#
	# loop over each city
	#

	$result = db_query("SELECT * FROM users");
	while ($row = db_fetch_hash($result)){

		echo "rebuilding user $row[email]...<br />\n";
		local_rebuild_user($row);
		echo "done<br />\n";
	}



	#
	# process all buildings in a city
	#

	function local_rebuild_user($user){

		login_set_user($user);

		$result = db_query("SELECT * FROM user_buildings WHERE user_id=$user[id]");
		while ($row = db_fetch_hash($result)){

			$i = init_building_instance($row);

			$i->b->on_refresh($i);

			$i->save_local_props();
		}

		login_save_global_props();
	}

	echo "all done!";

?>