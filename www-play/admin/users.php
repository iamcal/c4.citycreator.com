<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get users
	#

	$users = array();
	$smarty->assign_by_ref('users', $users);

	$result = db_query("SELECT * FROM users");
	while ($row = db_fetch_hash($result)){

		$users[] = $row;
	}


	#
	# output
	#

	$smarty->display('page_admin_users.txt');
?>