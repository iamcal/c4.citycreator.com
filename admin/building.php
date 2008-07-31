<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get building
	#

	$id = intval($_REQUEST[id]);

	$row = db_fetch_hash(db_query("SELECT * FROM class_buildings WHERE id=$id"));

	$smarty->assign_by_ref('building', $row);


	#
	# save changes?
	#

	if ($_POST[done]){

		$fields = explode(' ', 'uid name on_design on_build on_refresh on_event');
		$hash = array();

		foreach ($fields as $f){

			$hash[$f] = AddSlashes($_POST[$f]);
		}

		db_update('class_buildings', $hash, "id=$row[id]");

		header("location: buildings.php");
		exit;
	}


	#
	# output
	#

	$smarty->display('page_admin_building.txt');
?>