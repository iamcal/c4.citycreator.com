<?
	# $Id: logout.php,v 1.2 2004/07/08 23:45:11 Cal Henderson Exp $

	include('include/init.txt');

	login_clear_cookie();

	$cfg[user_ok] = 0;

	$smarty->display('page_logout.txt');
?>