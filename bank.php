<?
	# $Id: bank.php 2 2007-11-21 17:54:11Z iamcal $

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