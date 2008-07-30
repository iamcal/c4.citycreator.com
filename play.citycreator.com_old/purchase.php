<?
	# $Id: purchase.php,v 1.2 2004/09/21 02:47:05 Cal Henderson Exp $

	include('include/init.txt');

	login_check_loggedin();


	$buildings = array();

	$result = db_query("SELECT * FROM buildings WHERE is_visible=1 ORDER BY base_price ASC");
	while($row = db_fetch_array($result)){
		$buildings[] = $row;
	}

	$smarty->assign_by_ref('buildings', &$buildings);


	$smarty->display('page_purchase.txt');
?>