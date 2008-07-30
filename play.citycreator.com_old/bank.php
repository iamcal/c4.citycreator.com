<?
	# $Id: bank.php,v 1.1 2004/07/28 01:56:58 Cal Henderson Exp $

	include('include/init.txt');
	loadlib('bank');

	login_check_loggedin();

	#
	# calulate loan
	#

	$loan_maximum = bank_get_max_loan();
	$loan_available = max(0, $loan_maximum - $cfg[user][loan]);

	$smarty->assign('loan_maximum', $loan_maximum);
	$smarty->assign('loan_available', $loan_available);


	#
	# show page
	#

	$smarty->display('page_bank.txt');
?>