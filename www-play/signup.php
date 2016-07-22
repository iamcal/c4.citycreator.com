<?
	# $Id: signup.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	#
	# is user already logged in?
	#

	if ($cfg[user_ok]){
		header('location: /');
		exit;
	}


	#
	# check signup details
	#

	if ($_POST[done]){

		$name		= AddSlashes($_POST[name]);
		$email		= AddSlashes($_POST[email]);
		$password	= AddSlashes($_POST[password]);
		$city_name	= AddSlashes($_POST[city_name]);
		$job_title	= AddSlashes($_POST[job_title]);

		$uid = db_insert('users', array(

			'date_create'	=> time(),
			'email'		=> $email,
			'password'	=> $password,
			'name'		=> $name,
			'title'		=> $job_title,
			'avatar'	=> '',
			'color'		=> '',
			'city_name'	=> $city_name,
			'cash'		=> 1000,
			'loan'		=> 1000,
			'size_x_pos'	=> 0,
			'size_x_neg'	=> 0,
			'size_y_pos'	=> 0,
			'size_y_neg'	=> 0,
			'population'	=> 0,
			'state'		=> '',
		));

		login_set_cookie($uid, $_POST[password]);

		header('location: login_check.php?target='.urlencode('/'));
		exit;
	}


	#
	# display the signup page
	#

	$smarty->display('page_signup.txt');
?>