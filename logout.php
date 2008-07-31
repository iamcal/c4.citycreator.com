<?
	# $Id: logout.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_clear_cookie();

	$cfg[user_ok] = 0;

	$smarty->display('page_logout.txt');
?>