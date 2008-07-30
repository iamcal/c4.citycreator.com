<?
	# $Id: city.php,v 1.1 2004/07/09 02:21:33 Cal Henderson Exp $

	include('include/init.txt');

	login_check_loggedin();

	$smarty->display('page_city.txt');
?>