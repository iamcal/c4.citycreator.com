<?
	# $Id: login_check.php,v 1.2 2004/07/09 02:21:33 Cal Henderson Exp $

	include('include/init.txt');

	if ($cfg[user_ok]){

		if ($_GET[target]){

			header('location: '.$_GET[target]);
		}else{

			header('location: /');
		}

	}else{

		$smarty->display('page_login_check.txt');
	}
?>