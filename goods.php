<?
	# $Id$

	include('include/init.txt');

	login_check_loggedin();


	#
	# load goods instances
	#

	$goods = array();
	$smarty->assign_by_ref('goods', $goods);

	$result = db_query("SELECT * FROM user_goods WHERE user_id={$cfg[user][id]}");
	while ($row = db_fetch_hash($result)){

		$row[c] = db_fetch_hash(db_query("SELECT * FROM class_goods WHERE id=$row[goods_id]"));

		$goods[] = $row;
	}


	#
	# output
	#

	$smarty->display('page_goods.txt');
?>