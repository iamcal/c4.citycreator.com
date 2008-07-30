<?
	# $Id: account.php,v 1.1 2004/07/21 03:36:07 Cal Henderson Exp $

	include('include/init.txt');

	login_check_loggedin();

	$smarty->display('page_account.txt');
?>