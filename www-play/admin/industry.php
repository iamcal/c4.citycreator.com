<?
	#
	# $Id$
	#

	include('../include/init.txt');


	#
	# get industry
	#

	$id = intval($_REQUEST[id]);

	$row = db_fetch_hash(db_query("SELECT * FROM class_industries WHERE id=$id"));

	$smarty->assign_by_ref('industry', $row);


	#
	# save changes?
	#

	if ($_POST[done]){

		$fields = explode(' ', 'uid name on_build');
		$hash = array();

		foreach ($fields as $f){

			$hash[$f] = AddSlashes($_POST[$f]);
		}

		db_update('class_industries', $hash, "id=$row[id]");

		header("location: industries.php");
		exit;
	}


	#
	# output
	#

	$smarty->display('page_admin_industry.txt');
?>