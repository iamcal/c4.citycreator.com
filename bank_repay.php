<?
	# $Id: bank_repay.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');
	loadlib('bank');

	login_check_loggedin();


	#
	# amount ok?
	#

	$amount = Round($HTTP_POST_VARS[amount]);
	if ($amount < 1 || $amount > $cfg[user][loan]){

		$smarty->display('page_bank_repay_amount.txt');
		exit;
	}
	$loan_total = $cfg[user][loan] - $amount;

	$smarty->assign('loan_new', $amount);
	$smarty->assign('loan_total', $loan_total);

	$cash = $cfg[user][cash] - $amount;
	$smarty->assign('cash', $cash);


	#
	# repay the loan?
	#

	if ($HTTP_POST_VARS[done]){
		db_update("users", array(
			'loan' => $loan_total,
			'cash' => $cash,
		), "id='{$cfg[user][id]}'");

		$smarty->display('page_bank_repay_done.txt');
		exit;
	}


	#
	# show repay calculation page
	#

	$smarty->assign('loan_old_interest', Round($cfg[user][loan] / 100));
	$smarty->assign('loan_new_interest', Round($loan_total / 100));

	$smarty->display('page_bank_repay_calc.txt');
?>