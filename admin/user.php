<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get user
	#

	$id_enc = intval($_GET[id]);

	$user = db_fetch_hash(db_query("SELECT * FROM users WHERE id=$id_enc"));

	if (!$user[id]){

		echo "user $id_enc not found.";
		exit;
	}

	$user[global_props] = unserialize($user[global_props]);

	$smarty->assign_by_ref('user', $user);


	#
	# get buildings
	#

	$buildings = array();
	$smarty->assign_by_ref('buildings', $buildings);

	$result = db_query("SELECT * FROM user_buildings WHERE user_id=$user[id]");
	while ($row = db_fetch_hash($result)){

		$row[local_props] = unserialize($row[local_props]);

		$buildings[] = $row;
	}


	#
	# output
	#

	$smarty->display('page_admin_user.txt');
?>