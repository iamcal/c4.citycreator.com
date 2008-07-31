<?
	# $Id: login_check.php 2 2007-11-21 17:54:11Z iamcal $

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