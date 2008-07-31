<?
	# $Id: account.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();

	$smarty->display('page_account.txt');
?>