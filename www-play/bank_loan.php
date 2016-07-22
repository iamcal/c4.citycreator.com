<?
	# $Id: bank_loan.php 2 2007-11-21 17:54:11Z iamcal $

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
	# amount too high?
	#

	$amount = Round($HTTP_POST_VARS[amount]);
	if ($amount < 1 || $amount > $loan_available){

		$smarty->display('page_bank_loan_amount.txt');
		exit;
	}
	$loan_new = $amount;
	$loan_total = $cfg[user][loan] + $amount;

	$smarty->assign('loan_new', $loan_new);
	$smarty->assign('loan_total', $loan_total);

	$cash = $cfg[user][cash] + $loan_new;
	$smarty->assign('cash', $cash);


	#
	# take out the loan?
	#

	if ($HTTP_POST_VARS[done]){

		db_update("users", array(
			'loan' => $loan_total,
			'cash' => $cash,
		), "id='{$cfg[user][id]}'");

		$smarty->display('page_bank_loan_done.txt');
		exit;
	}


	#
	# show loan calculation page
	#

	$smarty->assign('loan_old_interest', Round($cfg[user][loan] / 100));
	$smarty->assign('loan_new_interest', Round($loan_total / 100));


	$smarty->display('page_bank_loan_calc.txt');
?>