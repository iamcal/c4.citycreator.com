<?
	#
	# $Id$
	#

	include('include/init.txt');

	loadlib('building');

	login_check_loggedin();


	#
	# load the instance data
	#

	$id_enc = intval($_GET[id]);

	$row = db_fetch_hash(db_query("SELECT * FROM user_buildings WHERE id=$id_enc AND user_id={$cfg[user][id]}"));

	if (!$row[id]){

		error_404();
	}


	#
	# init building instance
	#

	$i = init_building_instance($row);

	$smarty->assign_by_ref('i', $i);


	#
	# output
	#

	$smarty->display('page_building_instance.txt');
?>