<?
	# $Id: login.php,v 1.3 2006/03/17 03:00:39 cal Exp $

	include('include/init.txt');

	#
	# is user already logged in?
	#

	if ($cfg[user_ok]){
		header('location: /');
		exit;
	}


	#
	# check login details
	#

	if ($_POST[email] && $_POST[password]){

		$email = AddSlashes($_POST[email]);
		$password = AddSlashes($_POST[password]);

		if ($row = db_fetch_one("SELECT * FROM users WHERE email='$email' AND password='$password'")){

			login_set_cookie($row[id], $row[password]);

			header('location: login_check.php?target='.urlencode($_POST[target]));
			exit;
		}
	}


	#
	# display the login page
	#

	$smarty->display('page_login.txt');
?>